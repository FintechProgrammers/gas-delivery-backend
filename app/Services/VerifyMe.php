<?php


namespace App\Services;

use GuzzleHttp\Client;

class VerifyMe
{
    public function verifyNin($data)
    {
        $params = [
            'firstName' => $data['first_name'],
            'lastName' => $data['last_name'],
            'dob' => $data['date_of_birth'],
        ];

        $ninNumber = $data['nin_number'];

        return $this->handle("/v1/verifications/identities/nin/{$ninNumber}", 'POST', $params);
    }

    public function verifyDriverLicence($data)
    {
        $params = [
            'firstName' => $data['first_name'],
            'lastName' => $data['last_name'],
            'dob' => $data['date_of_birth'],
        ];

        $licenceNumber = $data['license_number'];

        return $this->handle("/v1/verifications/identities/drivers_license/{$licenceNumber}", 'POST', $params);
    }

    private function handle($uri = '/', $method = 'POST', $params = [])
    {
        try {
            $client = new Client();

            $token = config('constants.verifyme.secret');

            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ];

            $options = [
                'headers' => $headers,
                'json' => $params
            ];

            $res = $client->request($method, config('constants.verifyme.base_url') . $uri, $options);

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
