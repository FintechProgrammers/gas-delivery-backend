<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;


class Dojah
{
    function ninLookup(array $data)
    {
        return $this->handle("/api/v1/kyc/nin?nin={$data['nin']}", "GET");
    }

    function bvnLookup(array $data)
    {
        return $this->handle("/api/v1/kyc/bvn/full?bvn={$data['bvn']}", "GET");
    }

    function driverLicenceLookup(array $data)
    {
        return $this->handle("/api/v1/kyc/dl?license_number={$data['license_number']}", "GET");
    }

    function cacLookup(array $data)
    {
        return $this->handle("/api/v1/kyc/cac/basic?rc_number={$data['rcNumber']}&company_type={$data['companyType']}", "GET");
    }

    private function handle($uri = '/', $method = 'POST', $params = [])
    {
        try {
            $client = new Client();

            $token = config('dojah.private_key');

            $headers = [
                'Content-Type' => 'application/json',
                'AppId' => config('dojah.app_id'),
                'Authorization' => $token
            ];

            $options = [
                'headers' => $headers,
                'json' => $params
            ];

            $res = $client->request($method, config('dojah.base_url') . $uri, $options);

            $data = json_decode($res->getBody()->getContents(), true);

            return [
                'success' => true,
                'data' => $data['data'],
            ];
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Log the exception message
            sendToLog($e);

            // Get the response body from the exception
            if ($e->hasResponse()) {
                $responseBody = $e->getResponse()->getBody()->getContents();
                $responseArray = json_decode($responseBody, true);

                // Check if the JSON decoding was successful
                if (json_last_error() === JSON_ERROR_NONE) {
                    $responseArray['success'] = false;
                    return $responseArray;
                }
            }

            return [
                'success' => false,
                'message' => 'An error occurred while processing the request.',
            ];
        } catch (\Exception $e) {
            // Log other exceptions
            sendToLog($e->getMessage());

            return [
                'success' => false,
                'message' => 'An unexpected error occurred.',
            ];
        }
    }
}
