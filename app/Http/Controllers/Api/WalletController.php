<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddBankAccountRequest;
use App\Http\Requests\WithdrawalRequest;
use App\Http\Resources\BankResource;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\User;
use App\Services\Flutterwave;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    function withdraw(WithdrawalRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = $request->user();

            $wallet = $user->wallet;

            if ($wallet->balance < $validated['amount']) {
                return $this->sendError("Insufficient balance", [], 400);
            }

            $flutterWave = new Flutterwave();

            $response = $flutterWave->transfer([
                'bank_code' => $validated['bank_code'],
                'account_number' => $validated['account_number'],
                'amount' => $validated['amount'],
                'narration' => $validated['narration'],
                'reference' => generateReference()
            ]);

            if (!$response['success']) {
                return $this->sendError($response['message'], [], 500);
            }

            $closingBalance = $wallet->balance - $validated['amount'];

            $receiverInformation = [
                'bank_code' => $validated['bank_code'],
                'bank_name' => $response['data']['bank_name'],
                'account_number' => $validated['account_number'],
                'account_name' => $response['data']['account_name']
            ];

            // create transaction record
            $user->transactions()->create([
                'amount' => $validated['amount'],
                'action' => 'withdrawal',
                'type' => 'debit',
                'status' => 'pending',
                'reference' => $response['data']['reference'],
                'external_reference' => $response['data']['id'],
                'opening_balance' => $wallet->balance,
                'closing_balance' => $closingBalance,
                'narration' => $validated['narration'],
                'receiver_informations' => json_encode($receiverInformation)
            ]);

            $wallet->update([
                'balance' => $closingBalance
            ]);

            return $this->sendResponse([], "Withdrawal request submitted successfully", 201);
        } catch (\Exception $e) {
            sendToLog($e);
            return $this->sendError(serviceDownMessage(), [], 500);
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

        $flutterwave = new Flutterwave();

        $response = $flutterwave->accountLookup($validated['account_number'], $validated['bank_code']);

        if (!$response['success']) {
            return $this->sendError($response['message'], [], 500);
        }

        return $this->sendResponse($response['data']);
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
