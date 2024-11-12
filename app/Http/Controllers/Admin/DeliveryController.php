<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    function index()
    {
        return view('admin.delivery.index');
    }

    function filter(Request $request)
    {
        $dateFrom = ($request->filled('date_from')) ? Carbon::parse($request->date_from)->startOfDay() : null;

        $dateTo = ($request->filled('date_to')) ? Carbon::parse($request->date_to)->endOfDay() : null;

        $search = $request->filled('search') ? $request->search : null;
        $status = $request->filled('status')  ? $request->status : null;

        $query = Delivery::withTrashed();

        $query->when(!empty($search), fn ($query) => $query->where('reference', 'LIKE', "%{$search}%"))
            ->when(!empty($status), fn ($query) => $query->where('status', $status))
            ->when(!empty($dateFrom) && !empty($dateTo), fn ($query) => $query->whereBetween('created_at', [$dateFrom, $dateTo]))
            ->when(!empty($status) && !empty($dateFrom) && !empty($dateTo), fn ($query) => $query->where('status', $status)->whereBetween('created_at', [$dateFrom, $dateTo]));

        $data['deliveries'] = $query->paginate(50);

        return view('admin.delivery._table', $data);
    }

    function show(Delivery $delivery)
    {
    }
}