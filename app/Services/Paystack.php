<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Paystack
{
    public function initaitePayment($data)
    {
        $payload = [
            "email" => $data['email'],
            "amount" => $data['amount'],
            'currency' => $data['currency'],
            'reference' => $data['reference'],
            'metadata' => $data['metadata'],
            'channels' => ["card", "bank", "ussd", "mobile_money", "bank_transfer"]
        ];

        return $this->handle("/transaction/initialize", "POST", $payload);
    }

    public function transaction($reference)
    {
        return $this->handle("/transaction/verify/{$reference}", "GET");
    }

    function createRecipeint($data)
    {
        $payload = [
            "type" => "nuban",
            "name" => $data['account_name'],
            "account_number" => $data['account_number'],
            "bank_code" => $data['bank_code'],
            "currency" =>  "NGN"
        ];

        return $this->handle("/transferrecipient", "POST", $payload);
    }

    function resolveAcount($data)
    {
        return $this->handle("/bank/resolve?account_number={$data['account_number']}&bank_code={$data['account_number']}", "GET");
    }

    function getBanks()
    {
        return $this->handle("/bank", "GET");
    }

    public function transfer($data)
    {
        $payload = [
            "source" => "balance",
            "reason" => $data['narration'],
            "amount" => $data['amount'],
            "recipient" => $data['recipient']
        ];

        return $this->handle("/transfer", "POST", $payload);
    }

    public function createCustomer(array $data)
    {
        $payload = [
            "email" => $data['email'],
            "first_name" => $data['first_name'],
            "last_name" => $data['last_name'],
            'phone' => $data['phone_number']
        ];

        return $this->handle("/customer", "POST", $payload);
    }

    function createBankAccount(array $data)
    {
        $payload = [
            "customer" => $data['customer'],
            "preferred_bank" => config('paystack.preferred_bank')
        ];

        return $this->handle("/dedicated_account", "POST", $payload);
    }

    function assignBankAccount(array $data)
    {
        $payload = [
            "email" => $data['email'],
            "first_name" => $data['first_name'],
            "last_name" => $data['last_name'],
            "phone" =>  $data['phone_number'],
            "preferred_bank" => config('paystack.preferred_bank'),
            "country" => "NG"
        ];

        return $this->handle("/dedicated_account", "POST", $payload);
    }

    private function handle($uri = '/', $method = 'POST', $params = [])
    {
        try {
            $client = new Client();

            $token = config('paystack.secret_key');

            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ];

            $options = [
                'headers' => $headers,
                'json' => $params
            ];

            $res = $client->request($method, config('paystack.base_url') . $uri, $options);

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
