<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{
    function index()
    {
        return view('admin.settings.index');
    }

    public function getSettings(Request $request)
    {
        $settings = Setting::first();

        if (!$settings) {
            return $this->sendResponse([], 'No settings found.', Response::HTTP_OK);
        }

        return $this->sendResponse([
            'minimum_withdrawal_amount'     => $settings->minimum_withdrawal_amount,
            'maximum_withdrawal_amount'     => $settings->maximum_withdrawal_amount,
            'withdrawal_fee'                => $settings->withdrawal_fee,
            'delivery_rate_type'            => $settings->delivery_rate_type,
            'price_per_km'                  => $settings->price_per_km,
            'tiered_rates'                  => $settings->tiered_rates,
            'referral_is_active'            => (bool) $settings->referral_is_active,
            'referral_bonus_per_purchase'   => $settings->referral_bonus,
            'payment_methods'               => $settings->payment_methods,
            'rider_percentage'              => $settings->rider_percentage,
        ], 'Settings fetched successfully.', Response::HTTP_OK);
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
            'referral_is_active'           => 'nullable',
            'referral_bonus_per_purchase'  => 'nullable|numeric|min:0',
            'payment_methods'              => 'nullable|array',
            'rider_percentage'            => 'nullable|numeric|min:0|max:100',
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
            'referral_is_active'           => $request->has('referral_is_active'),
            'referral_bonus'  => $request->referral_bonus_per_purchase ?? 0,
            'payment_methods'              => $request->payment_methods ?? [],
            'rider_percentage'            => $request->rider_percentage ?? 0,
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
