<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddBankAccountRequest;
use App\Http\Requests\WithdrawalRequest;
use App\Http\Resources\BankResource;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Provider;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{

    public function withdraw(WithdrawalRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user();
        $wallet = $user->wallet;

        if ($wallet->balance < $validated['amount']) {
            return $this->sendError("Insufficient wallet balance.", [], 400);
        }

        $provider = Provider::where('is_default', true)
            ->where('has_transaction', true)
            ->first();

        if (!$provider) {
            sendToLog("No provider available for withdrawal.");
            return $this->sendError("Unable to complete your request at the moment. Please try again later.");
        }

        $validated['wallet'] = $wallet;
        $validated['user'] = $user;
        $validated['provider'] = $provider;

        switch (strtolower($provider->short_name)) {
            case 'providus':
                return app(\App\Http\Controllers\ProvidusController::class)->transfer($validated);

            case '9japay':
                return app(\App\Http\Controllers\NijaPayController::class)->transfer($validated);

            default:
                sendToLog("Unsupported withdrawal provider: " . $provider->short_name);
                return $this->sendError("Unable to complete your request at the moment. Please try again later.");
        }
    }

    public function fundWallet(Request $request)
    {
        $user = $request->user();

        $provider = Provider::where('is_default', true)
            ->where('has_account', true)
            ->first();

        if (!$provider) {
            sendToLog("No provider available for bank account.");
            return $this->sendError("Unable to complete your request at the moment. Please try again later.");
        }

        $validated['user'] = $user;
        $validated['provider'] = $provider;

        switch (strtolower($provider->short_name)) {
            case 'providus':
                return app(\App\Http\Controllers\ProvidusController::class)->createAccount($validated);

            case '9japay':
                return app(\App\Http\Controllers\NijaPayController::class)->createAccount($validated);

            default:
                sendToLog("Unsupported account provider: " . $provider->short_name);
                return $this->sendError("Unable to complete your request at the moment. Please try again later.");
        }
    }

    function getBanks()
    {

        // $provider = Provider::where('is_default', true)
        //     ->where('has_transaction', true)
        //     ->first();

        // if (!$provider) {
        //     sendToLog("No provider available for withdrawal.");
        //     return $this->sendError("Unable to complete your request at the moment. Please try again later.");
        // }

        // switch (strtolower($provider->short_name)) {
        //     case 'providus':
        //         return app(\App\Http\Controllers\ProvidusController::class)->getBanks();

        //     case '9japay':
        //         return app(\App\Http\Controllers\NijaPayController::class)->getBanks();

        //     default:
        //         sendToLog("Unsupported withdrawal provider: " . $provider->short_name);
        //         return $this->sendError("Unable to complete your request at the moment. Please try again later.");
        // }

        $banks = BankResource::collection(Bank::get());

        return $this->sendResponse($banks);
    }

    function accountLookup(\App\Http\Requests\AccountLookupRequest $request)
    {
        $validated = $request->validated();

        $provider = Provider::where('is_default', true)
            ->where('has_transaction', true)
            ->first();

        if (!$provider) {
            sendToLog("No provider available for withdrawal.");
            return $this->sendError("Unable to complete your request at the moment. Please try again later.");
        }


        switch (strtolower($provider->short_name)) {
            case 'providus':
                return app(\App\Http\Controllers\ProvidusController::class)->accountLookup($validated);

            case '9japay':
                return app(\App\Http\Controllers\NijaPayController::class)->accountLookup($validated);

            default:
                sendToLog("Unsupported withdrawal provider: " . $provider->short_name);
                return $this->sendError("Unable to complete your request at the moment. Please try again later.");
        }
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
