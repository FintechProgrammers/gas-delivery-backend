<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Service;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function index()
    {

        return view('user.dashboard.index');
    }
}
