<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserManagementController extends Controller
{
    const ERROR_RESPONSE = 'Unable to complete your request the moment.';

    function index()
    {
        return view('admin.users.index');
    }

    function filter(Request $request)
    {
        $dateFrom = ($request->filled('date_from')) ? Carbon::parse($request->date_from)->startOfDay() : null;

        $dateTo = ($request->filled('date_to')) ? Carbon::parse($request->date_to)->endOfDay() : null;

        $search = $request->filled('search') ? $request->search : null;
        $status = $request->filled('status')  ? $request->status : null;
        $accountType = $request->filled('account_type') ? $request->account_type : null;

        $query = User::withTrashed();

        $query = $query
            ->when(!empty($search), fn ($query) => $query->where('name', 'LIKE', "%{$search}%")->orWhere('email', 'LIKE', "%{$search}%")->orWhere('username', 'LIKE', "%{$search}%"))
            ->when(!empty($status), fn ($query) => $query->where('status', $status))
            ->when(!empty($accountType), fn ($query) => $accountType == 'ambassador' ? $query->where('is_ambassador', true) : $query->where('is_ambassador', false))
            ->when(!empty($dateFrom) && !empty($dateTo), fn ($query) => $query->whereBetween('created_at', [$dateFrom, $dateTo]))
            ->when(!empty($status) && !empty($dateFrom) && !empty($dateTo), fn ($query) => $query->where('status', $status)->whereBetween('created_at', [$dateFrom, $dateTo]))
            ->when(!empty($accountType) && !empty($dateFrom) && !empty($dateTo), fn ($query) => $accountType == 'ambassador' ? $query->where('is_ambassador', true)->whereBetween('created_at', [$dateFrom, $dateTo]) : $query->where('is_ambassador', false)->whereBetween('created_at', [$dateFrom, $dateTo]));

        $data['users'] = $query->paginate(50);

        return view('admin.users._table', $data);
    }

    function create()
    {
        return view('admin.users._user-form');
    }

    function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {

            DB::beginTransaction();

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'name'  => $request->fullname,
                'password' => Hash::make('default')
            ]);

            UserInfo::create([
                'user_id' => $user->id
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'User created successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e);
            return response()->json(['success' => false, 'message' => serviceDownMessage()], 500);
        }
    }

    function show(User $user)
    {
        $data['user'] = $user;

        return view('admin.users.show', $data);
    }

    function update(Request $request)
    {
    }

    function suspend(User $user)
    {
        $user->update([
            'status' => 'suspended',
        ]);

        return response()->json(['success' => true, 'message' => 'Suspended successfully.']);
    }

    function activate(User $user)
    {
        $user->update([
            'status' => 'active',
        ]);

        return response()->json(['success' => true, 'message' => 'Activated successfully.']);
    }

    function destroy(User $user)
    {

        $user->delete();

        return response()->json(['success' => true, 'message' => 'Deleted successfully.']);
    }
}
