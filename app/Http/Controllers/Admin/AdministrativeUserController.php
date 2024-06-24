<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;


class AdministrativeUserController extends Controller
{
    function index()
    {
        return view('admin.admins.index');
    }

    function filter(Request $request)
    {
        $data['admins'] = Admin::where('id', '!=', 1)->where('id', '!=', Auth::guard('admin')->user()->id)->latest()->paginate(50);

        return view('admin.admins._table', $data);
    }

    function create()
    {
        $data['roles'] = Role::select('id', 'name')->where('name', '<>', 'super admin')->orderBy('name', 'ASC')->get();

        return view('admin.admins.create', $data);
    }

    function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string',
            'email' => 'required|email|unique:admins,email',
            'roles'  => 'required'
        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {

            DB::beginTransaction();

            $admin = Admin::create([
                'email' => $request->email,
                'name'  => $request->fullname,
                'password' => Hash::make('default')
            ]);

            $admin->assignRole($request->roles);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Admin created successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e);
            return response()->json(['success' => false, 'message' => serviceDownMessage()], 500);
        }
    }

    function show(Admin $admin)
    {
        $data['admin'] = $admin;

        return view('admin.admins.show', $data);
    }

    function edit(Admin $admin)
    {
        $data['admin'] = $admin;

        $data['roles'] = Role::select('id', 'name')->where('name', '<>', 'super admin')->orderBy('name', 'ASC')->get();

        return view('admin.admins.edit', $data);
    }

    function update(Request $request, Admin $admin)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string',
            'roles'  => 'required'
        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {

            DB::beginTransaction();

            $admin->update([
                'name'  => $request->fullname,
            ]);

            $admin->syncRoles($request->roles);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Admin updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e);
            return response()->json(['success' => false, 'message' => serviceDownMessage()], 500);
        }
    }

    function suspend(Admin $admin)
    {
        $admin->update([
            'status' => 'suspended',
        ]);

        return response()->json(['success' => true, 'message' => 'Suspended successfully.']);
    }

    function activate(Admin $admin)
    {
        $admin->update([
            'status' => 'active',
        ]);

        return response()->json(['success' => true, 'message' => 'Activated successfully.']);
    }

    function destroy(Admin $admin)
    {

        $admin->delete();

        return response()->json(['success' => true, 'message' => 'Deleted successfully.']);
    }
}
