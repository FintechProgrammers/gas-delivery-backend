<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProvidusController extends Controller
{

    public function transfer($validated)
    {
        DB::beginTransaction();

        try {
            $providusService = new \App\Services\Providus();

            $wallet = $validated['wallet'];
            $user = $validated['user'];
            $provider = $validated['provider'];

            $reference = generateReference();

            $response = $providusService->fundTransfer([
                'account_name' => $validated['account_name'],
                'account_number' => $validated['account_number'],
                'bank_code' => $validated['bank_code'],
                'amount' => $validated['amount'],
                'narration' => $validated['narration'],
                'source_account_name' => config('app.name'),
                'reference' => $reference
            ]);

            if (!$response['success']) {
                DB::rollBack();
                return $this->sendError($response['message'] ?? 'Transfer failed', [], 500);
            }

            $data = $response['data'];
            $responseCode = trim($data['responseCode'] ?? '');

            // Only these codes mean success
            $successCodes = ['00', '36'];

            $receiverInformation = [
                'bank_code' => $validated['bank_code'],
                'bank_name' => $data['bank_name'] ?? null,
                'account_number' => $validated['account_number'],
                'account_name' => $data['account_name'] ?? $validated['account_name'],
            ];

            $transactionStatus = in_array($responseCode, $successCodes) ? 'completed' : 'failed';

            $closingBalance = $wallet->balance - $validated['amount'];

            // Always create transaction record for audit
            $user->transactions()->create([
                'amount' => $validated['amount'],
                'action' => 'withdrawal',
                'type' => 'debit',
                'provider_id' =>  $provider->id,
                'status' => $transactionStatus,
                'reference' => $reference,
                'external_reference' => $data['sessionId'] ?? null,
                'opening_balance' => $wallet->balance,
                'closing_balance' => $closingBalance,
                'narration' => $validated['narration'],
                'request_payload' => json_encode($validated),
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
                ? $this->sendResponse([], "Withdrawal completed successfully", 201)
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

            $providusService = new \App\Services\Providus();

            $payload = [
                'account_name' => $user->full_name,
                // 'bvn' => $user->bvn
            ];

            $response = $providusService->createDynamicBankAccount($payload);
            // $response = $providusService->createReservedBankAccount($payload);

            if (!$response['success']) {
                return $this->sendError($response['message'], [], 500);
            }

            $response = $response['data'];

            $bank = 'Providus';

            Wallet::updateOrCreate([
                'user_id' => $user->id
            ], [
                'provider_id' => $provider->id,
                'account_number' => $response['account_number'],
                'account_name' => $response['account_name'],
                'bank_name' => $bank
            ]);

            $data = [
                'account_number' => $response['account_number'],
                'account_name' =>  $response['account_name'],
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
            $providusService = new \App\Services\Providus();

            $payload = [
                "account_number" => $validated['account_number'],
                "bank_code" => $validated['bank_code']
            ];

            $response = $providusService->accountLookup($payload);

            if (!$response['success']) {
                return $this->sendError($response['message'], [], 500);
            }

            $response = $response['data'];

            $data = [
                'bank_code' => $response['bankCode'],
                'account_name' => $response['accountName'],
                'account_number' => $response['accountNumber'],
                'reference' => generateReference()
            ];

            return $this->sendResponse($data);
        } catch (\Exception $e) {
            DB::rollBack();
            sendToLog($e);
            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    public function webhook(Request $request)
    {
        $rawPayload = trim(file_get_contents('php://input'), "\xEF\xBB\xBF");

        try {
            $decoded = json_decode(mb_convert_encoding($rawPayload, 'UTF-8', 'UTF-8'), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            Log::warning('Providus webhook: Invalid JSON payload.', ['error' => $e->getMessage()]);
            return response()->json([
                'requestSuccessful' => true,
                'sessionId' => '',
                'responseMessage' => 'system failure, retry',
                'responseCode' => '03',
            ], Response::HTTP_OK);
        }

        if (empty($decoded)) {
            Log::warning('Providus webhook: Empty payload.');
            return response()->json([
                'requestSuccessful' => true,
                'sessionId' => '',
                'responseMessage' => 'system failure, retry',
                'responseCode' => '03',
            ], Response::HTTP_OK);
        }

        $providedSignature = $request->header('X-Auth-Signature');

        if (empty($providedSignature)) {
            Log::warning('X-Auth-Signature is missing from the request', ['payload' => $decoded]);
            return $this->rejectResponse($decoded['sessionId'] ?? null);
        }

        $expectedSignature = strtoupper(hash('sha512', config('providus.client_id') . ':' . config('providus.client_secret')));

        if (!hash_equals($expectedSignature, $providedSignature)) {
            Log::warning('Providus webhook: Invalid signature.', ['payload' => $decoded]);
            return $this->rejectResponse($decoded['sessionId'] ?? null);
        }

        $accountNumber = $decoded['accountNumber'] ?? null;
        if (!$accountNumber) {
            Log::warning('Providus webhook: Missing account number.', ['payload' => $decoded]);
            return $this->rejectResponse($decoded['sessionId'] ?? null);
        }

        $wallet = Wallet::where('account_number', $accountNumber)->first();
        if (!$wallet) {
            Log::warning("Providus webhook: Wallet not found for account number: {$accountNumber}");
            return $this->rejectResponse($decoded['sessionId'] ?? null);
        }

        $reference = $decoded['settlementId'] ?? null;
        if (!$reference) {
            Log::warning("Providus webhook: Missing settlement ID.", ['payload' => $decoded]);
            return $this->rejectResponse($decoded['sessionId'] ?? null);
        }

        // Prevent duplicate transaction
        if (Transaction::where('reference', $reference)->exists()) {
            Log::info("Providus webhook: Duplicate transaction: {$reference}");

            return response()->json([
                'requestSuccessful' => true,
                'sessionId' => $decoded['sessionId'],
                'responseMessage' => 'duplicate transaction',
                'responseCode' => '01',
            ], Response::HTTP_OK);
        }

        $user = $wallet->user;
        $amount = floatval($decoded['settledAmount'] ?? 0);
        $openingBalance = $wallet->balance;
        $closingBalance = $openingBalance + $amount;

        $receiverInfo = [
            'bank_code' => null,
            'bank_name' => $decoded['sourceBankName'] ?? null,
            'account_number' => $decoded['sourceAccountNumber'] ?? null,
            'account_name' => $decoded['sourceAccountName'] ?? null,
        ];

        // Create transaction record
        $transaction = $user->transactions()->create([
            'amount' => $amount,
            'action' => 'deposit',
            'type' => 'credit',
            'status' => 'completed',
            'reference' => $reference,
            'external_reference' => $reference,
            'opening_balance' => $openingBalance,
            'closing_balance' => $closingBalance,
            'narration' => $decoded['tranRemarks'] ?? 'Providus Settlement',
            'receiver_informations' => json_encode($receiverInfo),
            'response_payload' => $rawPayload,
        ]);

        // Update wallet balance
        $wallet->update([
            'balance' => $closingBalance,
        ]);

        // Send push notification
        $formattedAmount = number_format($amount, 2);
        sendPushNotification($user, "Your account has been credited with ₦{$formattedAmount}", "Credit Alert");

        return response()->json([
            'requestSuccessful' => true,
            'sessionId' => $decoded['sessionId'],
            'responseMessage' => 'success',
            'responseCode' => '00',
        ], Response::HTTP_OK);
    }

    protected function rejectResponse($sessionId = '99990000554443332221')
    {
        return response()->json([
            'requestSuccessful' => true,
            'sessionId' => $sessionId,
            'responseMessage' => 'rejected transaction',
            'responseCode' => '02',
        ], Response::HTTP_OK);
    }
}
