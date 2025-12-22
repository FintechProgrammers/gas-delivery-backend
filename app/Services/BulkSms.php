<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class BulkSms
{
    private Client $client;
    private string $baseUrl;
    private string $apiKey;
    private string $defaultSender;
    private int $timeout;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = config('constant.bulksms.base_url');
        $this->apiKey = config('constant.bulksms.api_key');
        $this->defaultSender = config('constant.bulksms.default_sender', 'FaastGas');
        $this->timeout = config('constant.bulksms.timeout', 30);

        $this->validateConfiguration();
    }

    /**
     * Send SMS to one or more recipients
     *
     * @param array $data Must contain 'to' and 'message'. Optional: 'from'
     * @return array
     */
    public function sendSms(array $data): array
    {
        $this->validateSmsData($data);

        $payload = [
            'to' => $data['to'],
            'from' => $data['from'] ?? $this->defaultSender,
            'body' => $data['message'],
        ];

        Log::info('Sending SMS', [
            'to' => $payload['to'],
            'from' => $payload['from'],
        ]);

        return $this->handle('/api/v2/sms', 'POST', $payload);
    }

    /**
     * Handle API requests
     *
     * @param string $uri
     * @param string $method
     * @param array $params
     * @return array
     */
    private function handle(string $uri, string $method = 'POST', array $params = []): array
    {

        logger($this->apiKey);
        try {
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ];

            $options = [
                'headers' => $headers,
                'json' => $params,
                'timeout' => $this->timeout,
                'connect_timeout' => 10,
            ];

            $response = $this->client->request(
                $method,
                $this->baseUrl . $uri,
                $options
            );

            $data = json_decode($response->getBody()->getContents(), true);

            Log::info('BulkSMS API Success', [
                'uri' => $uri,
                'status' => $response->getStatusCode(),
            ]);

            return [
                'success' => true,
                'data' => $data,
                'status_code' => $response->getStatusCode(),
            ];
        } catch (RequestException $e) {
            // Log::error('BulkSMS API Request Error', [
            //     'uri' => $uri,
            //     'error' => $e->getMessage(),
            //     'code' => $e->getCode(),
            // ]);

            if ($e->hasResponse()) {
                $responseBody = $e->getResponse()->getBody()->getContents();
                $statusCode = $e->getResponse()->getStatusCode();
                $responseArray = json_decode($responseBody, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($responseArray)) {
                    return array_merge($responseArray, [
                        'success' => false,
                        'status_code' => $statusCode,
                    ]);
                }

                return [
                    'success' => false,
                    'message' => 'API error: ' . $e->getMessage(),
                    'status_code' => $statusCode,
                ];
            }

            return [
                'success' => false,
                'message' => 'Network error: Unable to reach BulkSMS API',
            ];
        } catch (GuzzleException $e) {
            Log::error('BulkSMS Guzzle Exception', [
                'error' => $e->getMessage(),
                'uri' => $uri,
            ]);

            return [
                'success' => false,
                'message' => 'HTTP client error: ' . $e->getMessage(),
            ];
        } catch (\Exception $e) {
            Log::error('BulkSMS Unexpected Exception', [
                'error' => $e->getMessage(),
                'uri' => $uri,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Validate configuration on instantiation
     *
     * @throws InvalidArgumentException
     */
    private function validateConfiguration(): void
    {
        if (empty($this->baseUrl)) {
            throw new InvalidArgumentException('BulkSMS base URL is not configured');
        }

        if (empty($this->apiKey)) {
            throw new InvalidArgumentException('BulkSMS API key is not configured');
        }
    }

    /**
     * Validate SMS data
     *
     * @param array $data
     * @throws InvalidArgumentException
     */
    private function validateSmsData(array $data): void
    {
        if (!isset($data['to']) || empty($data['to'])) {
            throw new InvalidArgumentException('Recipient phone number (to) is required');
        }

        if (!isset($data['message']) || empty($data['message'])) {
            throw new InvalidArgumentException('Message body is required');
        }

        if (strlen($data['message']) > 1600) {
            throw new InvalidArgumentException('Message exceeds maximum length of 1600 characters');
        }
    }
}