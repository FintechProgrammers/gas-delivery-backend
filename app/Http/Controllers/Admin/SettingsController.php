<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    function index()
    {
        return view('admin.settings.index');
    }

    public function store(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'withdrawal_is_active'      => 'nullable|in:on',
            'minimum_withdrawal_amount' => 'nullable|numeric|min:0',
            'maximum_withdrawal_amount' => 'nullable|numeric|min:0',
            'withdrawal_fee'            => 'nullable|numeric|min:0',
            'delivery_rate_type'        => 'nullable|required|in:per_km,tiered',
            'price_per_km'              => 'nullable|required_if:delivery_rate_type,per_km|numeric|min:0',
            'tiered_rates'              => 'nullable|required_if:delivery_rate_type,tiered|array',
            'tiered_rates.*.min'        => 'nullable|required_if:delivery_rate_type,tiered|numeric|min:0',
            'tiered_rates.*.max'        => 'nullable|required_if:delivery_rate_type,tiered|numeric|min:0|gte:tiered_rates.*.min',
            'tiered_rates.*.price'      => 'nullable|required_if:delivery_rate_type,tiered|numeric|min:0',
        ]);


        if ($validator->fails()) {
            return $this->sendError("", $validator->errors(), 422);
        }

        // Prepare the settings data
        $data = [
            'minimum_withdrawal_amount' => $request->minimum_withdrawal_amount ?? 0,
            'maximum_withdrawal_amount' => $request->maximum_withdrawal_amount ?? 0,
            'withdrawal_fee'            => $request->withdrawal_fee ?? 0,
            'delivery_rate_type'        => $request->delivery_rate_type,
            'price_per_km'              => $request->delivery_rate_type === 'per_km' ? $request->price_per_km : 0,
            'tiered_rates'              => $request->delivery_rate_type === 'tiered' ? $request->tiered_rates : [],
        ];

        // Store or update the settings
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