<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddBankAccountRequest;
use App\Http\Requests\WithdrawalRequest;
use App\Http\Resources\BankResource;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\User;
use App\Models\Wallet;
use App\Services\Flutterwave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{

    public function withdraw(WithdrawalRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user();
        $wallet = $user->wallet;

        if ($wallet->balance < $validated['amount']) {
            return $this->sendError("Insufficient balance", [], 400);
        }

        DB::beginTransaction();

        try {
            $providusService = new \App\Services\Providus();

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

    public function fundWallet(Request $request)
    {
        $user = $request->user();

        try {

            $providusService = new \App\Services\Providus();

            $payload = [
                'account_name' => $user->full_name
            ];

            $response = $providusService->createDynamicBankAccount($payload);

            if (!$response['success']) {
                return $this->sendError($response['message'], [], 500);
            }

            $response = $response['data'];

            $bank = 'Providus';

            Wallet::updateOrCreate([
                'user_id' => $user->id
            ], [
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

    function getBanks()
    {
        $banks = BankResource::collection(Bank::get());

        return $this->sendResponse($banks);
    }

    function accountLookup(\App\Http\Requests\AccountLookupRequest $request)
    {
        $validated = $request->validated();

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
            'account_number' => $response['accountNumber']
        ];

        return $this->sendResponse($data);
    }

    function addAccount(AddBankAccountRequest $request)
    {
        try {

            $validated = $request->validated();

            $user = $request->user();

            $user->bankAccounts()->create([
                'account_number' => $validated['account_number'],
                'bank_code' => $validated['bank_code'],
                'account_name' => $validated['account_name'],
                'bank_name' => $validated['bank_name']
            ]);

            return $this->sendResponse([], "Bank account added successfully", 201);
        } catch (\Exception $e) {
            sendToLog($e);
            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    function bankAccounts(Request $request)
    {
        $user = $request->user();

        $accounts = $user->bankAccounts;

        return $this->sendResponse($accounts);
    }

    function deleteBankAccount(BankAccount $account)
    {
        $account->delete();

        return $this->sendResponse([], "Bank account deleted successfully");
    }
}
