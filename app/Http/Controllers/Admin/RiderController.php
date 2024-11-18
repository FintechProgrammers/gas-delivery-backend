<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RiderController extends Controller
{
    function index()
    {
        return view('admin.rider.index');
    }

    function filter(Request $request)
    {
        $dateFrom = ($request->filled('date_from')) ? Carbon::parse($request->date_from)->startOfDay() : null;

        $dateTo = ($request->filled('date_to')) ? Carbon::parse($request->date_to)->endOfDay() : null;

        $search = $request->filled('search') ? $request->search : null;
        $status = $request->filled('status')  ? $request->status : null;
        $accountType = $request->filled('account_type') ? $request->account_type : null;

        $query = User::where('account_type', 'RIDER')->withTrashed();

        $query = $query
            ->when(!empty($search), fn($query) => $query->where('name', 'LIKE', "%{$search}%")->orWhere('email', 'LIKE', "%{$search}%")->orWhere('username', 'LIKE', "%{$search}%"))
            ->when(!empty($status), fn($query) => $query->where('status', $status))
            ->when(!empty($dateFrom) && !empty($dateTo), fn($query) => $query->whereBetween('created_at', [$dateFrom, $dateTo]))
            ->when(!empty($status) && !empty($dateFrom) && !empty($dateTo), fn($query) => $query->where('status', $status)->whereBetween('created_at', [$dateFrom, $dateTo]));

        $data['users'] = $query->paginate(50);

        return view('admin.rider._table', $data);
    }
}
