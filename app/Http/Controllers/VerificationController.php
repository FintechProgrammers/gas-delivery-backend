<?php

namespace App\Http\Controllers;

use App\Models\UserKyc;
use App\Services\Dojah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
    protected $dojahService;

    function __construct(Dojah $dojah)
    {
        $this->dojahService = $dojah;
    }

    public function verifyNin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nin_number' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->sendError("", ['errors' => $validator->errors()], 422);
        }

        try {
            $payload = ['nin' => $request->nin_number];

            $response = $this->dojahService->ninLookup($payload);

            if (!isset($response['success']) || ! $response['success']) {
                return $this->sendError($response['message']);
            }

            $entity = $response['data']['entity'];
            $user = auth()->user();

            // Compare details
            if (
                strtolower($entity['first_name']) !== strtolower($user->first_name) ||
                strtolower($entity['last_name']) !== strtolower($user->last_name) ||
                $entity['date_of_birth'] !== $user->date_of_birth
            ) {
                return $this->sendError("Invalid NIN information.", [], 400);
            }

            UserKyc::create([
                'user_id' => $user->id,
                'docx_number' => $request->nin_number,
                'service' => 'nin',
                'response' =>  $entity,
                'status' => 'approved'
            ]);

            return $this->sendResponse($entity, "Nin Verified successfully");
        } catch (\Exception $e) {
            logger($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    public function verifyDriversLicence(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'license_number' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError("", ['errors' => $validator->errors()], 422);
        }

        try {
            $payload = ['license_number' => $request->license_number];

            $response = $this->dojahService->driverLicenceLookup($payload);

            if (!isset($response['success']) || ! $response['success']) {
                return $this->sendError($response['message']);
            }

            $entity = $response['data']['entity'];
            $user = auth()->user();

            // Compare details
            if (
                strtolower($entity['firstName']) !== strtolower($user->first_name) ||
                strtolower($entity['lastName']) !== strtolower($user->last_name) ||
                $entity['birthDate'] !== $user->date_of_birth
            ) {
                return $this->sendError("Invalid Information.", [], 400);
            }

            UserKyc::create([
                'user_id' => $user->id,
                'docx_number' => $request->license_number,
                'service' => 'drivers_license',
                'response' =>  $entity,
                'status' => 'approved'
            ]);

            return $this->sendResponse($entity, "Drivers Licence Verified successfully");
        } catch (\Exception $e) {
            logger($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    public function verifyBnv(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bvn_number' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->sendError("", ['errors' => $validator->errors()], 422);
        }

        try {
            $payload = ['bvn' => $request->bvn_number];

            $response = $this->dojahService->bvnLookup($payload);

            if (!isset($response['success']) || ! $response['success']) {
                return $this->sendError($response['message']);
            }

            $entity = $response['data']['entity'];
            $user = $request->user();

            // Compare details
            if (
                strtolower($entity['first_name']) !== strtolower($user->first_name) ||
                strtolower($entity['last_name']) !== strtolower($user->last_name) ||
                $entity['date_of_birth'] !== $user->date_of_birth
            ) {
                return $this->sendError("Invalid Information.", [], 400);
            }

            $user->update([
                'bvn' => $request->bvn_number,
                'bvn_verified_at' => now()
            ]);

            UserKyc::create([
                'user_id' => $user->id,
                'docx_number' => $request->bvn_number,
                'service' => 'bvn',
                'response' =>  $entity,
                'status' => 'approved'
            ]);

            return $this->sendResponse($entity, "BVN Verified successfully");
        } catch (\Exception $e) {
            logger($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    public function verifyCAC(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rc_number' => ['required', 'numeric'],
            'company_type' => ['required']
        ]);

        if ($validator->fails()) {
            return $this->sendError("", ['errors' => $validator->errors()], 422);
        }

        try {
            $payload = ['rcNumber' => $request->rc_number, 'company_type' => $request->company_type];

            $response = $this->dojahService->cacLookup($payload);

            if (!isset($response['success']) || ! $response['success']) {
                return $this->sendError($response['message']);
            }

            $entity = $response['data']['entity'];
            $user = auth()->user();

            // Compare company-related details
            if (
                strtolower($entity['company_name']) !== strtolower($user->company_name) ||
                strtolower($entity['type_of_company']) !== strtolower($request->company_type)
            ) {
                return $this->sendError("Invalid Information.", [], 400);
            }

            UserKyc::create([
                'user_id' => $user->id,
                'docx_number' => $request->rc_number,
                'service' => 'cac',
                'response' =>  $entity,
                'status' => 'approved'
            ]);

            return $this->sendResponse($entity, "CAC Verified successfully");
        } catch (\Exception $e) {
            logger($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }
}
