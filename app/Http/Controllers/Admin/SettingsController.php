<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    function index()
    {
        return view('admin.settings.index');
    }

    function store(Request $request)
    {
        $data = [
            'withdrawal_is_active'      => $request->withdrawal_is_active === "on" ? true : false,
            'minimum_withdrawal_amount' => $request->has('minimum_withdrawal_amount') ? $request->minimum_withdrawal_amount : 0,
            'maximum_withdrawal_amount' => $request->has('maximum_withdrawal_amount') ? $request->maximum_withdrawal_amount : 0,
            'withdrawal_fee'            => $request->has('withdrawal_fee') ? $request->withdrawal_fee : 0,
            'price_per_km'             => $request->has('bv_equivalent') ? $request->price_per_km : 0,
        ];

        $settings = Setting::first();

        if ($settings) {
            $settings->update($data);
        } else {
            Setting::create($data);
        }

        return $this->sendResponse([], "Settings updated successfully.");
    }

    function getBanks()
    {
        $flutterwave = new \App\Services\Flutterwave();

        $response = $flutterwave->getBanks();

        if (!$response['success']) {
            return $this->sendError($response['message'], [], 500);
        }

        $response = $response['data'];

        foreach ($response as $val) {
            \App\Models\Bank::updateOrCreate(
                ['bank_code' => $val['code']],
                ['bank_name' => $val['name']]
            );
        }

        return
            $response;
    }
}
