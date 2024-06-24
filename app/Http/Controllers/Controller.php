<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    /**
     * Summary of sendResponse
     * @param mixed $result
     * @param mixed $message
     * @param mixed $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($result, $message = '', $code = Response::HTTP_OK, $links = [], $meta = [])
    {
        $response = [
            'success' => true,
            'data' => !empty($result) ? $result : null,
            'message' => $message,
        ];

        if (!empty($links)) {
            $response['links'] = $links;
        }

        if (!empty($meta)) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $code);
    }

    /**
     * Summary of sendError
     * @param mixed $error
     * @param mixed $errorMessages
     * @param mixed $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($error, $errorMessages = [], $code = Response::HTTP_NOT_FOUND)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['errors'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
