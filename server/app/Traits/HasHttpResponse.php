<?php

namespace App\Traits;

trait HasHttpResponse
{
    public function serverError($payload = null, $message = 'Server error', $code = 500)
    {
        return $this->response($payload, $message, $code);
    }

    public function badRequest($payload = null, $message = 'Bad request', $code = 400)
    {
        return $this->response($payload, $message, $code);
    }

    public function unauthorized($payload = null, $message = "Unauthorized", $code = 401)
    {
        return $this->response($payload, $message, $code);
    }

    public function response($payload = null, $message = 'ok', $code = 200)
    {
        return response()->json([
            'message' => $message,
            'payload' => $payload,
        ], $code);
    }
}
