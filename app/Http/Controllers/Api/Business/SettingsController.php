<?php

namespace App\Http\Controllers\Api\Business;

use App\Http\Controllers\Controller;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{
    function openingDays(Request $request)
    {
        $request->validate([
            'opening_days' => ['required'],
            'opening_hours' => ['required'],
        ]);

        $user = $request->user();

        UserInfo::where('user_id', $user->id)->update([
            'opening_days' => $request->opening_days,
            'opening_hours' => $request->opening_hours
        ]);

        return $this->sendResponse([], "Opening hours updated successfully", Response::HTTP_CREATED);
    }

    function toggleAvailability(Request $request)
    {
        $user = $request->user();

        $user->update([
            'is_available' => !$user->is_available
        ]);

        return $this->sendResponse([], "Availablity updated successfully", Response::HTTP_CREATED);
    }
}
