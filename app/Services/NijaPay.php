<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class NijaPay
{

    public function createPermanentAccount(array $data)
    {
        $payload = [
            'requestReference' => $data['reference'],
            'accountName' => $data['account_name'],
            'autoPayoutEnabled' => true
        ];

        return $this->handle('/v1/api/virtual-accounts/permanent', "POST", $payload);
    }

    public function createTemporaryAccount(array $data)
    {
        $payload = [
            'requestReference' => $data['reference'],
            'timeToLive' => 120,
            'amount' => null,
            'IsSinglePayment' => true
        ];

        return $this->handle('/v1/api/virtual-accounts/transient', "POST", $payload);
    }

    public function virtualAccounts($pageNumber = 1)
    {
        return $this->handle("/v1/api/virtual-accounts?page-size=10&page-number={$pageNumber}", "GET");
    }

    public function getTotalBalance()
    {
        return $this->handle('/v1/api/virtual-accounts/total-balance', "GET");
    }

    public function getAccountBalance($accountNumber)
    {
        return $this->handle("/v1/api/virtual-accounts/balance/{$accountNumber}", "GET");
    }

    public function getBankList()
    {
        return $this->handle('/v1/api/banks', "GET");
    }

    public function nameEnquiry(array $data)
    {
        $payload = [
            'bankCode' => $data['bank_code'],
            'accountNumber' => $data['account_number']
        ];

        return $this->handle('/v1/api/transfers/name-enquiry', 'POST', $payload);
    }

    public function transfer($data)
    {
        $payload = [
            'paymentReference' => $data['reference'],
            'senderAccountNumber' => $data['sender_account_number'],
            'senderAccountName' => $data['sender_account_name'],
            'recipientAccountNumber' => $data['recipient_account_number'],
            'recipientAccountName' => $data['recipient_account_name'],
            'recipientBankCode' => $data['recipient_bank_code'],
            'amount' => $data['amount'],
            'nameEnquiryReference' => $data['name_enquiry_reference'],
            'narration' => $data['narration']
        ];

        return $this->handle('/v1/api/transfers', "POST", $payload);
    }

    public function getTransactionStatus($reference)
    {
        return $this->handle("/v1/api/transfers/tsq/{$reference}", "GET");
    }

    public function getTransaction($transactionId)
    {
        return $this->handle("/v1/api/transactions/{$transactionId}", "GET");
    }

    public function getStimuteDeposit($data)
    {
        $payload = [
            'recipientAccountNumber' => $data['recipient_account_number'],
            'amount' => $data['amount'],
            'authKey' => config('nijapay.auth_key')
        ];

        return $this->handle("/v1/api/transactions/simulate-deposit", "POST", $payload);
    }

    private function handle($uri = '/', $method = 'POST', $params = [])
    {
        try {
            $client = new Client();

            $apiKey = config('nijapay.api_key'); // 
            $secret = config('nijapay.secret'); //

            $headers = [
                'Content-Type' => 'application/json',
                'api-key' => $apiKey,
                'secret' => $secret
            ];

            $url = config('nijapay.base_url');

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
