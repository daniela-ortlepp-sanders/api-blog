<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Concerns\InteractsWithInput;

class IpBlock
{
    public function handle(Request $request, Closure $next)
    {
        $whiteListIps = \Config::get('variables.white_list_ips');

        $localIP = $_SERVER['SERVER_ADDR'];

        if (!in_array($localIP, $whiteListIps)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }

}