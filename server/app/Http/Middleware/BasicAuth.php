<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BasicAuth extends BaseMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        $username = $request->getUser();
        $password = $request->getPassword();

        $expectedUsername = env('BASIC_AUTH_USERNAME');
        $expectedPassword = env('BASIC_AUTH_PASSWORD');

        if (!($expectedUsername && $expectedPassword)) {
            return $this->serverErrorResponse(message: 'Basic authentication not configured');
        }

        if ($username !== $expectedUsername || $password !== $expectedPassword) {
            return $this->unauthorizedResponse()
                ->header('WWW-Authenticate', 'Basic realm="API"');
        }

        return $next($request);
    }
}
