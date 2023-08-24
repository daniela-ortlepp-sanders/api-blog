<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Concerns\InteractsWithInput;

class ApiKeyAuth
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-KEY');
        $envKey = env('API_KEY');

        if ($apiKey != $envKey) {
            return response()->json(['message' => 'Invalid API key', 'env' => $envKey], 401);
        }

        return $next($request);
    }

}