<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Flutterwave
{
    function checkout($data)
    {
        $payload = [
            'tx_ref' => $data['reference'],
            'amount' => $data['amount'],
            'currency' => 'NGN',
            'redirect_url' => $data['redirect_url'],
            'customer' => [
                'email' => $data['email'],
            ],
            'customizations' => [
                'title' => $data['purchase_name'],
            ],
            'meta' => [
                'user_id' => $data['user_id'],
            ]
        ];

        return self::handle("/v3/payments", "POST", $payload);
    }

    function getBanks()
    {
        return $this->handle("/v3/banks/NG", "GET");
    }

    function accountLookup($accountNumber, $bankCode)
    {
        $payload = [
            'account_number' => $accountNumber,
            'account_bank' => $bankCode,
        ];

        return $this->handle("/v3/accounts/resolve", "POST", $payload);
    }

    function transfer($data)
    {
        $payload = [
            "account_bank" => $data['bank_code'],
            "account_number" => $data['account_number'],
            "amount" => $data['amount'],
            "narration" => $data['narration'],
            "currency" => "NGN",
            "reference" => $data['reference'],
            "callback_url" => "",
            "debit_currency" => "NGN"
        ];

        return $this->handle("/v3/transfers" . "POST", $payload);
    }

    function getTransfer($transferId)
    {
        return $this->handle("/v3/transfers/{$transferId}", "GET");
    }

    function createAccount($data)
    {
        $payload = [
            "email" => $data['email'],
            "bvn" => $data['bvn'],
            "is_permanent" => false,
        ];

        return $this->handle("/v3/virtual-account-numbers", "POST", $payload);
    }
    private function handle($uri = '/', $method = 'POST', $params = [])
    {
        try {
            $client = new Client();

            $token = config('constant.flutterwave.secret_key');

            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ];

            $options = [
                'headers' => $headers,
                'json' => $params
            ];

            $res = $client->request($method, config('constant.flutterwave.base_url') . $uri, $options);

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
