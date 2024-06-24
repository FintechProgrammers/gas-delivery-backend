<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        return view('auth.register', ['request' => $request]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'referral_code' => ['nullable', 'string', 'exists:' . User::class],
            'username' => ['required', 'string', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            DB::beginTransaction();

            $parent = null;

            // Check if referral code is not empty then assign parent to the person registering.
            if ($request->filled('referral_code')) {
                // get user that has code
                $parent = User::where('referral_code', $request->referral_code)->value('id');

                if (empty($parent)) {
                    return $this->sendError('The referral code you entered is not valid.');
                }
            }

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'parent_id' => $parent,
                'password' => Hash::make($request->password),
            ]);

            DB::commit();

            event(new Registered($user));

            Auth::login($user);

            $route = route('dashboard', absolute: false);

            return $this->sendResponse(['route' => $route], "You have been registered successfully", 201);
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e);
            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }
}
