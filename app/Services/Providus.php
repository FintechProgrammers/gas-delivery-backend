<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;


class Providus
{

    public function createDynamicBankAccount(array $data)
    {
        $payload = [
            'account_name' => $data['account_name']
        ];

        return $this->handle("/PiPCreateDynamicAccountNumber", "POST", $payload);
    }

    public function createReservedBankAccount(array $data)
    {
        $payload = [
            'account_name' => $data['account_name'],
            'bnv' => $data['bvn']
        ];

        return $this->handle("/PiPCreateReservedAccountNumber", "POST", $payload);
    }

    public function verifyTransactionBySessionId($sessionId)
    {
        return $this->handle("/PiPverifyTransaction_sessionid?session_id={$sessionId}", "GET");
    }

    public function verifyTransactionBySettlementId($settlementId)
    {
        return $this->handle("/PiPverifyTransaction_sessionid?settlement_id={$settlementId}", "GET");
    }

    public function updateAccountName(array $data)
    {
        $payload = [
            "account_number" => $data['account_number'],
            "account_name" => $data['account_name']
        ];

        return $this->handle("/PiPUpdateAccountName", "POST", $payload);
    }

    public function accountLookup(array $data)
    {
        $payload = [
            'accountNumber' => $data['account_number'],
            'beneficiaryBank' => $data['bank_code'],
        ];

        return $this->handle("/GetNIPAccount", "POST", $payload, true);
    }

    public function fundTransfer(array $data)
    {
        $payload = [
            'beneficiaryAccountName' => $data['account_name'],
            'beneficiaryAccountNumber' => $data['account_number'],
            'beneficiaryBank'   => $data['bank_code'],
            'transactionAmount' => $data['amount'],
            'currencyCode' => 'NGN',
            'narration' => $data['narration'],
            'sourceAccountName' => $data['source_account_name'],
            'transactionReference' => $data['reference']
        ];

        return $this->handle("/NIPFundTransfer", "POST", $payload, true);
    }

    public function providusFundTransfer(array $data)
    {
        $payload = [
            'creditAccount' => $data['creditAccount'],
            'debitAccount' => $data['debitAccount'],
            'transactionAmount' => $data['amount'],
            'currencyCode' => 'NGN',
            'narration' => $data['narration'],
            'transactionReference' => $data['reference']
        ];

        return $this->handle("/ProvidusFundTransfer ", "POST", $payload, true);
    }

    public function getTransactionStatus($transactionReference)
    {
        $payload = [
            'transactionReference' => $transactionReference
        ];

        return $this->handle("/GetNIPTransactionStatus", "POST", $payload, true);
    }

    public function getProvidusTransactionStatus($transactionReference)
    {
        $payload = [
            'transactionReference' => $transactionReference
        ];

        return $this->handle("/GetProvidusTransactionStatus", "POST", $payload, true);
    }

    public function getAccountDetails(array $data)
    {
        $payload = [
            'accountNumber' => $data['account_number']
        ];

        return $this->handle("/GetProvidusAccount", "POST", $payload, true);
    }

    private function handle($uri = '/', $method = 'POST', $params = [], bool $isPayments = false)
    {
        try {
            $client = new Client();

            $clientId = config('providus.client_id'); // 
            $clientSecret = config('providus.client_secret'); //

            // Generate the signature as SHA512(ClientId:ClientSecret)
            $signatureString = $clientId . ':' . $clientSecret;
            $signatureHash = hash('sha512', $signatureString);

            $headers = [
                'Content-Type' => 'application/json',
                'X-Auth-Signature' => $signatureHash,
                'Client-Id' => $clientId
            ];

            if ($isPayments) {
                $params["userName"] = config('providus.username');
                $params["password"] = config('providus.password');

                $url = config('providus.payment_url');
            } else {
                $url = config('providus.base_url');
            }

            $options = [
                'headers' => $headers,
                'json' => $params
            ];

            $res = $client->request($method,  $url . $uri, $options);

            $data = json_decode($res->getBody()->getContents(), true);

            return [
                'success' => true,
                'data' => $data,
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
