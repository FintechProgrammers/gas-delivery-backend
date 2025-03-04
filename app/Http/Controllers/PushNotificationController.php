<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushNotificationController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function userSubscribed()
    {
        try {
            $data = trim(file_get_contents('php://input'), "\xEF\xBB\xBF");

            // Decode the contents from the webhook response
            $decoded = json_decode(mb_convert_encoding($data, 'UTF-8', 'UTF-8'), true, 512, JSON_THROW_ON_ERROR);

            $userPushId = $decoded['user_id'];

            // Check if user_push_id already exists
            $existingUser = User::where('user_push_id', $userPushId)->get()->first();

            if (! $existingUser) {
                User::whereUuid($this->user->uuid)->update([
                    'user_push_token' => $decoded['push_token'],
                    'user_push_id' => $decoded['user_id'],
                ]);

                return $this->sendResponse([], 'Push notification subscription successful.');
            }
        } catch (\Throwable $th) {
            // sendToSlack($th);
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
