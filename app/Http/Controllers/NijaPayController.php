<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Aws\Api\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class NijaPayController extends Controller
{
    public function transfer($validated)
    {
        DB::beginTransaction();

        try {

            $service = new \App\Services\NijaPay();

            $wallet = $validated['wallet'];
            $user = $validated['user'];
            $provider = $validated['provider'];

            $reference = generateReference();

            $requestPayload = [
                'reference' => $reference,
                'sender_account_number' => config('nijapay.sending_account'),
                'sender_account_name' => config('nijapay.sender_name'),
                'recipient_account_number' => $validated['account_number'],
                'recipient_account_name' => $validated['account_name'],
                'recipient_bank_code' => $validated['bank_code'],
                'name_enquiry_reference' => $validated['reference'],
                'narration' =>  $validated['narration'],
                'amount' => $validated['amount'] * 100
            ];

            $response = $service->transfer($requestPayload);

            if (!$response['success']) {
                DB::rollBack();
                return $this->sendError($response['message'] ?? 'Transfer failed', [], 500);
            }

            $data = $response['data'];
            $responseCode = trim($data['statusCode'] ?? '');

            $receiverInformation = [
                'bank_code' => $validated['bank_code'],
                'bank_name' => $data['bank_name'] ?? null,
                'account_number' => $validated['account_number'],
                'account_name' => $data['account_name'] ?? $validated['account_name'],
            ];

            if ($responseCode === '00') {
                $transactionStatus = 'completed';
            } else if ($responseCode === '01') {
                $transactionStatus = 'pending';
            } else {
                $transactionStatus = 'failed';
            }

            $closingBalance = $wallet->balance - $validated['amount'];

            // Always create transaction record for audit
            $user->transactions()->create([
                'amount' => $validated['amount'],
                'action' => 'withdrawal',
                'type' => 'debit',
                'provider_id' =>  $provider->id,
                'status' => $transactionStatus,
                'reference' => $reference,
                'external_reference' => $data['data']['id'] ?? null,
                'opening_balance' => $wallet->balance,
                'closing_balance' => $closingBalance,
                'narration' => $validated['narration'],
                'request_payload' => json_encode($requestPayload),
                'receiver_informations' => json_encode($receiverInformation),
                'response_payload' => json_encode($data)
            ]);

            // Deduct only if successful
            if ($transactionStatus === 'completed') {
                $wallet->update([
                    'balance' => $closingBalance
                ]);

                $amount = number_format($validated['amount'], 2);

                $title = "Debit Alart";
                $message = "Your account have been debit with {$amount} NGN";

                sendPushNotification($user, $message, $title);
            }

            DB::commit();

            return $transactionStatus === 'completed'
                ? $this->sendResponse([], $data['message'], 201)
                : $this->sendError("Withdrawal failed", [], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            sendToLog($e);
            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    public function createAccount($validated)
    {
        try {

            $user = $validated['user'];
            $provider = $validated['provider'];

            $service = new \App\Services\NijaPay();

            $payload = [
                'account_name' => $user->full_name,
                'reference' => generateReference()
                // 'bvn' => $user->bvn
            ];

            $response = $service->createTemporaryAccount($payload);
            // $response = $service->createPermanentAccount($payload);

            if (!$response['success']) {
                sendToLog($response);
                return $this->sendError(serviceDownMessage(), [], 500);
            }

            $response = $response['data'];

            $bank = '9jaPay Mfb';

            if ($response['statusCode'] != '00') {
                return $this->sendError($response['message'], [], 500);
            }

            $accountNumber = $response['statusCode'] === '00' ? $response['data']['accountNumber'] : null;
            $accountName = config('nijapay.sender_name');

            Wallet::updateOrCreate([
                'user_id' => $user->id
            ], [
                'provider_id' => $provider->id,
                'reference' => $payload['reference'],
                'account_number' => $accountNumber,
                'account_name' => $accountName,
                'bank_name' => $bank
            ]);

            $data = [
                'account_number' => $accountNumber,
                'account_name' =>  $accountName,
                'bank_name' => $bank
            ];

            return $this->sendResponse($data, "Bank account");
        } catch (\Exception $e) {
            logger($e);
            return $this->sendError(serviceDownMessage());
        }
    }

    public function accountLookup($validated)
    {

        try {
            $service = new \App\Services\NijaPay();

            $payload = [
                "account_number" => $validated['account_number'],
                "bank_code" => $validated['bank_code']
            ];

            $response = $service->nameEnquiry($payload);

            if (!$response['success']) {
                return $this->sendError($response['message'], [], 500);
            }

            $response = $response['data'];

            if ($response['statusCode'] != '00') {
                return $this->sendError($response['message'], [], 500);
            }

            $data = [
                'bank_code' => $response['data']['bankCode'],
                'account_name' => $response['data']['accountName'],
                'account_number' => $response['data']['accountNumber'],
                'reference' => $response['data']['nameEnquiryReference']
            ];

            return $this->sendResponse($data);
        } catch (\Exception $e) {
            DB::rollBack();
            sendToLog($e);
            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    public function getBanks()
    {

        $service = new \App\Services\NijaPay();

        $response = $service->getBankList();

        if (!$response['success']) {
            sendToLog($response);
            return $this->sendError(serviceDownMessage(), [], 500);
        }

        $response = $response['data'];

        if ($response['statusCode'] != '00') {
            return $this->sendError($response['message'], [], 500);
        }

        return $response;
    }

    public function webhook(Request $request)
    {
        $rawPayload = trim(file_get_contents('php://input'), "\xEF\xBB\xBF");

        try {
            $decoded = json_decode(mb_convert_encoding($rawPayload, 'UTF-8', 'UTF-8'), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            Log::warning('9japay webhook: Invalid JSON.', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Invalid JSON payload'], Response::HTTP_OK);
        }

        if (empty($decoded)) {
            Log::warning('9japay webhook: Empty payload.');
            return response()->json(['message' => 'Empty payload'], Response::HTTP_OK);
        }

        // Verify signature
        $providedSignature = $request->header('SECRET_KEY');
        $expectedSignature = strtoupper(hash('sha256', config('nijapay.secret') . ':' . config('providus.client_secret')));

        if (empty($providedSignature) || !hash_equals($expectedSignature, $providedSignature)) {
            Log::warning('9japay webhook: Invalid or missing signature.', ['provided' => $providedSignature]);
            return $this->rejectResponse($decoded['eventId'] ?? null);
        }

        // Handle event
        if (($decoded['eventType'] ?? null) !== 'new_transaction') {
            Log::info('9japay webhook: Ignored non-transaction event.', ['eventType' => $decoded['eventType']]);
            return response()->json([], Response::HTTP_OK);
        }

        $data = $decoded['data'] ?? [];
        $accountNumber = $data['accountNumber'] ?? null;
        $reference = $data['transactionReference'] ?? null;

        if (!$accountNumber || !$reference) {
            Log::warning('9japay webhook: Missing account number or reference.', ['payload' => $decoded]);
            return $this->rejectResponse($decoded['eventId'] ?? null);
        }

        $wallet = Wallet::where('account_number', $accountNumber)->first();
        if (!$wallet) {
            Log::warning("9japay webhook: Wallet not found for account number {$accountNumber}");
            return $this->rejectResponse($decoded['eventId'] ?? null);
        }

        if (Transaction::where('reference', $reference)->exists()) {
            Log::info("9japay webhook: Duplicate transaction: {$reference}");
            return response()->json([], Response::HTTP_OK);
        }

        $amount = $data['amount'] / 100; // kobo to naira
        $openingBalance = $wallet->balance;
        $closingBalance = $openingBalance + $amount;

        $receiverInfo = [
            'bank_code' => $data['senderBankCode'] ?? null,
            'bank_name' => $data['senderBank'] ?? null,
            'account_number' => $data['senderAccountNumber'] ?? null,
            'account_name' => $data['senderName'] ?? null,
        ];

        // Create transaction
        $transaction = $wallet->user->transactions()->create([
            'amount' => $amount,
            'action' => 'deposit',
            'type' => strtolower($data['transactionType'] ?? 'credit'),
            'status' => 'completed',
            'reference' => $reference,
            'external_reference' => $data['id'],
            'opening_balance' => $openingBalance,
            'closing_balance' => $closingBalance,
            'narration' => $data['narration'] ?? null,
            'receiver_informations' => json_encode($receiverInfo),
            'response_payload' => $rawPayload,
            'transaction_date' => $data['transactionDate'] ?? now(),
        ]);

        // Update balance
        $wallet->update(['balance' => $closingBalance]);

        // Notify user
        $formattedAmount = number_format($amount, 2);
        sendPushNotification($wallet->user, "Your account has been credited with ₦{$formattedAmount}", "Credit Alert");

        Log::info("9japay webhook: Transaction successfully processed.", ['reference' => $reference]);

        return response()->json(['message' => 'Success'], Response::HTTP_OK);
    }

    private function rejectResponse($eventId = null)
    {
        return response()->json([
            'status' => 'rejected',
            'eventId' => $eventId,
        ], Response::HTTP_OK);
    }
}
