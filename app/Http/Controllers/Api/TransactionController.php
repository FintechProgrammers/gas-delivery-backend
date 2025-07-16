<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::where('user_id', $request->user()->id)->paginate(10);

        // Return pagination metadata along with data
        return $this->sendResponse([
            'data' => TransactionResource::collection($transactions),
            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
                'from' => $transactions->firstItem(),
                'to' => $transactions->lastItem(),
            ]
        ], 'Transactions fetched successfully.', Response::HTTP_OK);
    }

    function show(Transaction $transaction)
    {
        $transaction = new TransactionResource($transaction);

        return $this->sendResponse($transaction, "", Response::HTTP_OK);
    }
}
