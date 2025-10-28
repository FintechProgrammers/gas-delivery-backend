<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DriverLicenseRequest;
use App\Http\Requests\VerifyNin;
use App\Models\User;
use App\Models\UserKyc;
use App\Services\VerifyMe;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KycController extends Controller
{

    protected $service;

    function __construct()
    {
        $this->service = new VerifyMe();
    }

    function verifyNIN(VerifyNin $request)
    {
        try {

            $validated = (object) $request->validated();

            $user = $request->user();

            $payload = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'date_of_birth' => $user->date_of_birth,
                'nin_number' => $validated->nin_number
            ];

            $response = $this->service->verifyNIN($payload);

            if (!$response['success']) {
                return $this->sendError($response['message'], [], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $response = $response['data'];

            // check name matches
            if (!$response['fieldMatches']['firstname'] || !$response['fieldMatches']['lastname']) {
                return $this->sendError("NIN details do not match user details", [], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // check date of birth matches
            if (!$response['fieldMatches']['dob']) {
                return $this->sendError("NIN details do not match user details", [], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            UserKyc::create([
                'user_id' => $user->id,
                'service' => 'nin',
                'status' => 'approved',
                'response' => json_encode($response)
            ]);

            return $this->sendResponse(null, "NIN verified successfully", Response::HTTP_OK);
        } catch (\Exception $e) {
            logger($e);
            return $this->sendError(serviceDownMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    function verifyDriverLicence(DriverLicenseRequest $request)
    {
        try {

            $validated = (object) $request->validated();

            $user = $request->user();

            $payload = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'date_of_birth' => $user->date_of_birth,
                'license_number' => $validated->license_number
            ];

            $response = $this->service->verifyDriverLicence($payload);

            if (!$response['success']) {
                return $this->sendError($response['message'], [], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $response = $response['data'];

            // check name matches
            if (!$response['fieldMatches']['firstname'] || !$response['fieldMatches']['lastname']) {
                return $this->sendError("License details do not match user details", [], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // check date of birth matches
            if (!$response['fieldMatches']['dob']) {
                return $this->sendError("License details do not match user details", [], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            UserKyc::create([
                'user_id' => $user->id,
                'service' => 'drivers_license',
                'status' => 'approved',
                'response' => json_encode($response)
            ]);

            $user->update([
                'kyc_verified_at' => now()
            ]);

            return $this->sendResponse(null, "License verified successfully", Response::HTTP_OK);
        } catch (\Exception $e) {
            logger($e);
            return $this->sendError(serviceDownMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
