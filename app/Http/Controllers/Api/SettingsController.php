<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    function getPaymentMethods()
    {
        $paymentMethods = paymentMethods();

        return $this->sendResponse($paymentMethods, "Payment methods");
    }
}
