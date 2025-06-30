<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProvidusController extends Controller
{
    function webhook(Request $request)
    {
        // Fetch raw input and decode JSON payload
        $data = trim(file_get_contents('php://input'), "\xEF\xBB\xBF");

        try {
            $decoded = json_decode(mb_convert_encoding($data, 'UTF-8', 'UTF-8'), true, 512, JSON_THROW_ON_ERROR);

            logger($decoded);
        } catch (\JsonException $e) {
            Log::error('Invalid JSON payload', ['exception' => $e]);
            return response('Invalid JSON payload', Response::HTTP_BAD_REQUEST)->header('Content-Type', 'text/plain');
        }
    }
}
