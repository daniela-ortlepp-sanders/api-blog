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

        if ($apiKey !== env('API_KEY')) {
            return response()->json(['message' => 'Invalid API key ' . $apiKey], 401);
        }

        return $next($request);
    }

}