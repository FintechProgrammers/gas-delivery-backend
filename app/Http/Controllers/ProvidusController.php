<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProvidusController extends Controller
{
    public function webhook(Request $request)
    {
        $rawPayload = trim(file_get_contents('php://input'), "\xEF\xBB\xBF");

        try {
            $decoded = json_decode(mb_convert_encoding($rawPayload, 'UTF-8', 'UTF-8'), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            Log::warning('Providus webhook: Invalid JSON payload.', ['error' => $e->getMessage()]);
            return $this->rejectResponse();
        }

        if (empty($decoded)) {
            Log::warning('Providus webhook: Empty payload.');
            return $this->rejectResponse();
        }

        $providedSignature = $request->header('X-Auth-Signature');
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
            'responseMessage' => 'accepted',
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
