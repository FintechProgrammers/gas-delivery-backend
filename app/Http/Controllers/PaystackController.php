<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\DepositAccount;
use App\Models\PaystackCustomer;
use App\Models\User;
use App\Services\Paystack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

class PaystackController extends Controller
{
    protected $paystackService;

    function __construct()
    {
        $this->paystackService = new Paystack();
    }


    function webhook(Request $request)
    {
        // Fetch raw input and decode JSON payload
        $data = trim(file_get_contents('php://input'), "\xEF\xBB\xBF");

        try {
            $decoded = json_decode(mb_convert_encoding($data, 'UTF-8', 'UTF-8'), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            Log::error('Invalid JSON payload', ['exception' => $e]);
            return response('Invalid JSON payload', Response::HTTP_BAD_REQUEST)->header('Content-Type', 'text/plain');
        }

        $headerKey = $request->headers->get('X-Paystack-Signature');
        $signature = hash_hmac('sha512', $data, config('paystack.secret_key'));

        if ($headerKey !== $signature) {
            return response('Unauthorized', Response::HTTP_UNAUTHORIZED)->header('Content-Type', 'text/plain');
        }

        try {

            $event = $decoded['event'];

            $data = $decoded['data'];

            if ($event === "charge.success") {
                $this->chargeSuccess($data);
            } else if ($event === "dedicatedaccount.assign.success") {
                $this->assignBankAccount($data);
            }

            return response('Webhook processed successfully', Response::HTTP_OK)->header('Content-Type', 'text/plain');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(
                'Error processing webhook',
                ['exception' => $e]
            );
            return response('Internal server error', Response::HTTP_INTERNAL_SERVER_ERROR)->header('Content-Type', 'text/plain');
        }
    }

    function chargeSuccess($data)
    {
        $channel = $data['channel'];

        if ($channel === "card") {
        } else {
        }
    }

    function assignBankAccount($data)
    {
        $user = User::where('email', $data['customer']['email'])->first();

        if (empty($user)) {
            return response('Account processed successfully', Response::HTTP_OK)->header('Content-Type', 'text/plain');
        }

        $paystackCustomer = PaystackCustomer::where('user_id', $user->id)->first();

        if (empty($paystackCustomer)) {
            return response('Account processed successfully', Response::HTTP_OK)->header('Content-Type', 'text/plain');
        }

        $paystackCustomer->update([
            'customer_id' => $data['customer']['id'],
            'response' => json_encode($data['customer'])
        ]);

        DepositAccount::create([
            'user_id' => $user->id,
            'account_number' => $data['dedicated_account']['account_number'],
            'account_name' => $data['dedicated_account']['account_name'],
            'bank_name' => $data['dedicated_account']['bank']['name'],
            'response' => json_encode($data['dedicated_account'])
        ]);
    }



    function failedResponse()
    {
        return throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => serviceDownMessage(),
        ], Response::HTTP_INTERNAL_SERVER_ERROR));
    }
}
