<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    function index(Request $request)
    {
        $transactions = Transaction::where('user_id', $request->user()->id)->paginate(10);

        $transactions = TransactionResource::class($transactions);

        return $this->sendResponse($transactions, "", Response::HTTP_OK);
    }

    function show(Transaction $transaction)
    {
        $transaction = new TransactionResource($transaction);

        return $this->sendResponse($transaction, "", Response::HTTP_OK);
    }
}
