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

    public function badRequestResponse($message = 'Bad request', $payload = null, $code = 400)
    {
        return $this->responseJson($payload, $message, $code);
    }

    public function unauthorizedResponse($message = "Unauthorized", $payload = null, $code = 401)
    {
        return $this->responseJson($payload, $message, $code);
    }

    public function forbiddenResponse($message = "Forbidden", $payload = null, $code = 403)
    {
        return $this->responseJson($payload, $message, $code);
    }

    public function notFoundResponse($message = "Not found", $payload = null, $code = 404)
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
