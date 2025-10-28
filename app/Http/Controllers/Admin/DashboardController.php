<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Order; // Assuming an Order model for sales
use App\Models\Deposit; // Assuming a Deposit model or similar for ranks/deposits
use App\Models\GasOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data['stats'] = $this->statistics();

        return view('admin.dashboard.index', $data);
    }

    /**
     * Retrieve statistics for the dashboard.
     *
     * @return array
     */
    public function statistics()
    {
        // Total customers (users with account_type 'CUSTOMER')
        $totalCustomers = User::where('account_type', 'CUSTOMER')->count();

        // Total riders (assuming riders have account_type 'RIDER')
        $totalRiders = User::where('account_type', 'RIDER')->count();

        // Total vendors (assuming vendors have account_type 'BUSINESS')
        $totalVendors = User::where('account_type', 'BUSINESS')->count();

        // Total sales (summing total_amount from GasOrder)
        $productsCount = GasOrder::where('status', 'completed')->sum('total_amount');

        // Total deposits (summing amount from Transaction with action 'deposit')
        $deposits = Transaction::where('action', 'deposit')->where('status', 'completed')->sum('amount');

        // Total withdrawals (summing amount from Transaction with action 'withdrawal')
        $withdrawals = Transaction::where('action', 'withdrawal')->where('status', 'completed')->sum('amount');

        return [
            (object) [
                'title' => 'Total Customers',
                'value' => formatNumber($totalCustomers), // Format count without NGN
                'color' => 'text-bg-info',
                'icon' => 'iconoir-community',
                'link' => null,
            ],
            (object) [
                'title' => 'Total Riders',
                'value' => formatNumber($totalRiders), // Format count without NGN
                'color' => 'text-bg-success',
                'icon' => 'iconoir-user-cart',
                'link' => null,
            ],
            (object) [
                'title' => 'Total Vendors',
                'value' => formatNumber($totalVendors), // Format count without NGN
                'color' => 'text-bg-warning',
                'icon' => 'iconoir-home-user',
                'link' => null,
            ],
            (object) [
                'title' => 'Sales',
                'value' => formatNumber($productsCount, true), // Format with NGN
                'color' => 'text-bg-success',
                'icon' => 'las la-sitemap',
                'link' => null,
            ],
            (object) [
                'title' => 'Deposits',
                'value' => formatNumber($deposits, true), // Format with NGN
                'color' => 'text-bg-info',
                'icon' => 'iconoir-lot-of-cash',
                'link' => null,
            ],
            (object) [
                'title' => 'Withdrawals',
                'value' => formatNumber($withdrawals, true), // Format with NGN
                'color' => 'text-bg-dark',
                'icon' => 'iconoir-hand-cash',
                'link' => null,
            ],
        ];
    }

    /**
     * Get revenue overview data for charts.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function revenueOverview(Request $request)
    {
        // Get the filter period from the request (default to 'This Year')
        $period = $request->query('period', 'This Year');

        // Initialize response structure
        $response = [
            'monthDataSeries1' => ['prices' => [], 'dates' => []],
            'monthDataSeries2' => ['prices' => [], 'dates' => []],
            'monthDataSeries3' => ['prices' => [], 'dates' => []],
            'visitors' => ['data' => [], 'categories' => ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']],
        ];

        // Define date range based on period
        switch ($period) {
            case 'Today':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                $days = 1; // Single day for short series
                break;
            case 'Last Week':
                $startDate = Carbon::now()->subWeek();
                $endDate = Carbon::now();
                $days = 7; // Last 7 days
                break;
            case 'Last Month':
                $startDate = Carbon::now()->subMonth();
                $endDate = Carbon::now();
                $days = 20; // Last 20 days for short series
                break;
            case 'This Year':
            default:
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now();
                $days = 20; // Last 20 days for short series, full year for long series
                break;
        }

        // monthDataSeries1: Income (e.g., completed order amounts)
        $incomeData = GasOrder::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // monthDataSeries2: Expenses (e.g., withdrawals or refunds)
        $expenseData = Transaction::where('action', 'withdrawal')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // monthDataSeries3: Long-term revenue trend (e.g., daily revenue over months)
        $longTermData = GasOrder::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Visitors: Daily user activity (e.g., user logins or registrations per day of week)
        $visitorData = User::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DAYNAME(created_at) as day, COUNT(*) as count')
            ->groupBy('day')
            ->orderByRaw("FIELD(day, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')")
            ->get();

        // Fill short-term series (monthDataSeries1 and monthDataSeries2)
        $currentDate = $startDate->copy();
        for ($i = 0; $i < min($days, 20); $i++) {
            $dateStr = $currentDate->format('d M Y');
            $response['monthDataSeries1']['dates'][] = $dateStr;
            $response['monthDataSeries2']['dates'][] = $dateStr;

            // Find income for this date
            $income = $incomeData->firstWhere('date', $currentDate->toDateString());
            $response['monthDataSeries1']['prices'][] = $income ? (float)$income->total : 0;

            // Find expense for this date
            $expense = $expenseData->firstWhere('date', $currentDate->toDateString());
            $response['monthDataSeries2']['prices'][] = $expense ? (float)$expense->total : 0;

            $currentDate->addDay();
        }

        // Fill long-term series (monthDataSeries3)
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('d M Y');
            $response['monthDataSeries3']['dates'][] = $dateStr;

            // Find revenue for this date
            $revenue = $longTermData->firstWhere('date', $currentDate->toDateString());
            $response['monthDataSeries3']['prices'][] = $revenue ? (float)$revenue->total : 0;

            $currentDate->addDay();
        }

        // Fill visitors data (weekly)
        $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        foreach ($daysOfWeek as $day) {
            $visitor = $visitorData->firstWhere('day', $day);
            $response['visitors']['data'][] = $visitor ? (int)$visitor->count : 0;
        }

        return response()->json($response);
    }

    /**
     * Get customer growth data for charts.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customerGrowth(Request $request)
    {
        // Get the filter period from the request (default to 'This Year')
        $period = $request->query('period', 'This Year');

        // Initialize response structure
        $response = [
            'series' => [
                ['name' => 'New Customers', 'data' => []],
                ['name' => 'Returning Customers', 'data' => []],
            ],
            'categories' => ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        ];

        // Define date range based on period
        switch ($period) {
            case 'Today':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                break;
            case 'Last Week':
                $startDate = Carbon::now()->startOfWeek()->subWeek();
                $endDate = Carbon::now()->endOfWeek()->subWeek();
                break;
            case 'Last Month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'This Year':
            default:
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now();
                break;
        }

        // New Customers: Count users created per day of the week, filtered by account_type
        $newCustomers = User::whereIn('account_type', ['BUSINESS', 'CUSTOMER', 'RIDER'])
            ->whereBetween('users.created_at', [$startDate, $endDate])
            ->selectRaw('DAYNAME(users.created_at) as day, COUNT(*) as count')
            ->groupBy('day')
            ->orderByRaw("FIELD(day, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')")
            ->get();

        // Returning Customers: Count distinct users with orders (excluding new users in the period)
        $returningCustomers = GasOrder::whereBetween('gas_orders.created_at', [$startDate, $endDate])
            ->join('users', 'gas_orders.user_id', '=', 'users.id')
            ->whereIn('users.account_type', ['BUSINESS', 'CUSTOMER', 'RIDER'])
            ->where('users.created_at', '<', $startDate)
            ->whereNull('gas_orders.deleted_at') // Handle soft deletes
            ->selectRaw('DAYNAME(gas_orders.created_at) as day, COUNT(DISTINCT users.id) as count')
            ->groupBy('day')
            ->orderByRaw("FIELD(day, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')")
            ->get();

        // Fill data for each day of the week
        $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        foreach ($daysOfWeek as $day) {
            // New Customers
            $newCustomer = $newCustomers->firstWhere('day', $day);
            $response['series'][0]['data'][] = $newCustomer ? (int)$newCustomer->count : 0;

            // Returning Customers
            $returningCustomer = $returningCustomers->firstWhere('day', $day);
            $response['series'][1]['data'][] = $returningCustomer ? (int)$returningCustomer->count : 0;
        }

        return response()->json($response);
    }
}
