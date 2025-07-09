<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Container\Container;

class ApiRateLimit
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next): Response
    {

        $key = $request->user()?->id ?: $request->ip();
        if ($this->limiter->tooManyAttempts($key, config('throttling.maxAttempts'))) {
            return response()->json([
                'message' => 'Too many requests. Please try again later.',
            ], 429);
        }
        $this->limiter->hit($key, 60);
        return $next($request);
    }
}
