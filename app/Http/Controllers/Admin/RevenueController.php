<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Revenue;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    function index()
    {
        return view('admin.revenue.index');
    }

    function filter(Request $request)
    {
        $query = Revenue::query();

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $revenues = $query->with('order')->latest()->paginate(10);

        return view('admin.revenue._table', compact('revenues'));
    }
}
