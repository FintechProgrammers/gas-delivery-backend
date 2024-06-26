<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    function index(Request $request)
    {
        $user = $request->user();

        $user = new UserResource($user);

        return $this->sendResponse($user);
    }
}
