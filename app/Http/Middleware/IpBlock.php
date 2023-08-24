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
        $whiteListIps = ['192.168.3.6', '172.31.64.100', '172.30.192.1'];

        $localIP = getHostByName(php_uname('n'));

        if (!in_array($localIP, $whiteListIps)) {
            return response()->json([
                'error' => 'Unauthorized',
                'ip' => $localIP,
                'white list' => $whiteListIps
            ], 401);
        }

        return $next($request);
    }

}