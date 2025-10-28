<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    function index()
    {
        return view('admin.transaction.index');
    }

    function filter(Request $request)
    {
        $dateFrom = ($request->filled('date_from')) ? Carbon::parse($request->date_from)->startOfDay() : null;

        $dateTo = ($request->filled('date_to')) ? Carbon::parse($request->date_to)->endOfDay() : null;

        $search = $request->filled('search') ? $request->search : null;
        $status = $request->filled('status')  ? $request->status : null;
        $action = $request->filled('action')  ? $request->action : null;
        $type = $request->filled('type')  ? $request->type : null;

        $query = Transaction::query();

        $query->when(!empty($search), fn($query) => $query->where('reference', 'LIKE', "%{$search}%"))
            ->when(!empty($status), fn($query) => $query->where('status', $status))
            ->when(!empty($action), fn($query) => $query->where('action', $status))
            ->when(!empty($type), fn($query) => $query->where('type', $type))
            ->when(!empty($dateFrom) && !empty($dateTo), fn($query) => $query->whereBetween('created_at', [$dateFrom, $dateTo]))
            ->when(!empty($status) && !empty($dateFrom) && !empty($dateTo), fn($query) => $query->where('status', $status)->whereBetween('created_at', [$dateFrom, $dateTo]));

        $data['transactions'] = $query->paginate(50);

        return view('admin.transaction._table', $data);
    }

    function show(Transaction $transaction)
    {
        $data['transaction'] = $transaction;

        return view('admin.transaction.show', $data);
    }
}
