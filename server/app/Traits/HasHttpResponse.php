<?php

namespace App\Traits;

trait HasHttpResponse
{
    public function serverErrorResponse(mixed $payload = null, string $message = 'Server error', int $code = 500)
    {
        return $this->responseJson($payload, $message, $code);
    }
    public function notImplementedResponse(mixed $payload = null, string $message = 'Not implemented', int $code = 501)
    {
        return $this->responseJson($payload, $message, $code);
    }

    public function badRequestResponse(string $message = 'Bad request', mixed $payload = null, int $code = 400)
    {
        return $this->responseJson($payload, $message, $code);
    }

    public function unauthorizedResponse(string $message = "Unauthorized", mixed $payload = null, int $code = 401)
    {
        return $this->responseJson($payload, $message, $code);
    }

    public function forbiddenResponse(string $message = "Forbidden", mixed $payload = null, int $code = 403)
    {
        return $this->responseJson($payload, $message, $code);
    }

    public function notFoundResponse(string $message = "Not found", mixed $payload = null, int $code = 404)
    {
        return $this->responseJson($payload, $message, $code);
    }

    public function responseJson(mixed $payload = null, string $message = 'ok', int $code = 200)
    {
        return response()->json([
            'message' => $message,
            'payload' => $payload,
        ], $code);
    }
}
