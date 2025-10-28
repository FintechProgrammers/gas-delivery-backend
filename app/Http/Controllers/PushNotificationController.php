<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PushNotificationController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function userSubscribed(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'push_token' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation error.', $validator->errors(), 422);
            }

            $user = $request->user();

            // Update user push token
            $user->update([
                'user_push_id' => $request->push_token,
            ]);

            return $this->sendResponse([], 'Push notification subscription successful.');
        } catch (\Throwable $th) {
            // Optionally send the error to Slack or log it
            // sendToSlack($th);
            logger()->error('Failed to subscribe user for push notifications', [
                'error' => $th->getMessage(),
                'user_id' => $this->user->id ?? null,
            ]);

            return $this->sendError('Something went wrong. Please try again later.', [], 500);
        }
    }

    public function userUnSubscribed()
    {
        User::whereId($this->user->id)->update([
            'user_push_token' => '',
            'user_push_id' => '',
        ]);

        return $this->sendResponse([], 'Push subscription unsubscribed successful.');
    }
}
