<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Token;
use Carbon\Carbon;

use App\Services\UtilService;

class VerifyJwtToken
{
    public function handle($request, Closure $next)
    {
        $utilService = app()->make(UtilService::class);
        $headerToken = $utilService->bearerToken($request);

        if (!$headerToken) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        // Decodifica o token JWT
        try {
            $token = new Token($headerToken);
            $payload = JWTAuth::decode($token);

            $exp = date("Y-m-d H:i:s",$payload->get('exp'));
            $now = Carbon::now();
            
            // Verifica se o token expirou
            if ($payload->get('exp') < $now->timestamp) {
                return response()->json(['message' => 'Token expired'], 401);
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['message' => 'Token expired'], 401);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['message' => 'Invalid token'], 401);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['message' => 'Token absent'], 401);
        }

        return $next($request);
    }
}