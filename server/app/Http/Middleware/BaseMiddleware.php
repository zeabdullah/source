<?php

namespace App\Http\Middleware;

use App\Traits\HasHttpResponse;

abstract class BaseMiddleware
{
    use HasHttpResponse;
}
