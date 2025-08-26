<?php

namespace App\Traits;

trait HasHttpResponse
{
    public function serverErrorResponse($payload = null, $message = 'Server error', $code = 500)
    {
        return $this->responseJson($payload, $message, $code);
    }
    public function notImplementedResponse($payload = null, $message = 'Not implemented', $code = 501)
    {
        return $this->responseJson($payload, $message, $code);
    }

    public function badRequestResponse($payload = null, $message = 'Bad request', $code = 400)
    {
        return $this->responseJson($payload, $message, $code);
    }

    public function unauthorizedResponse($payload = null, $message = "Unauthorized", $code = 401)
    {
        return $this->responseJson($payload, $message, $code);
    }

    public function notFoundResponse($payload = null, $message = "Not found", $code = 404)
    {
        return $this->responseJson($payload, $message, $code);
    }

    public function responseJson($payload = null, $message = 'ok', $code = 200)
    {
        return response()->json([
            'message' => $message,
            'payload' => $payload,
        ], $code);
    }
}
