<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WithdrawalRequest;
use App\Http\Resources\BankResource;
use App\Models\Bank;
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
}
