<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Token;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Illuminate\Support\Str;
use Concerns\InteractsWithInput;
use Carbon\Carbon;

use App\Services\UtilService;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $apiKey = $request->header('X-API-KEY');

        $tokenId    = base64_encode(random_bytes(32));
        $issuedAt   = time();
        $notBefore  = $issuedAt + 10;
        $exp        = Carbon::now()->addMinutes(60)->setTimezone('America/Sao_Paulo')->timestamp;
        $serverName = $_SERVER['SERVER_NAME'];

        $secretKey = base64_decode(env('JWT_SECRET'));
        $requestTime = date('Y-m-d H:i:s', time());
        $data = ['requestTime' => $requestTime];

        $payload = [
            'iat'  => $issuedAt,
            'jti'  => $tokenId,
            'iss'  => $serverName,
            'nbf'  => $notBefore,
            'exp'  => $exp,
            'data' => $data
        ];

        try {
            $customClaims = JWTFactory::customClaims($data);
            $payload = JWTFactory::make($data);

            $token = JWTAuth::encode($payload);

            return response()->json([
                'status' => 'success',
                'authorization' => [
                    'token' => $token->get(),
                    'type' => 'bearer',
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function refresh(Request $request)
    {
        try {
            $utilService = app()->make(UtilService::class);
            $headerToken = $utilService->bearerToken($request);

            $token = new Token($headerToken);
            #dd(var_dump($token));
            if (!$token) {
                return response()->json(['error' => 'Token not provided'], 401);
            }

            $newToken = JWTAuth::refresh($token);
            // send the refreshed token back to the client
            $request->headers->set('Authorization', 'Bearer ' . $newToken->get());

            return response()->json([
                'status' => 'success',
                'authorization' => [
                    'token' => $newToken->get(),
                    'exp' => $exp,
                    'type' => 'bearer',
                ]
            ]);
            #return response()->json(['token' => $newToken]);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        }
    }
}
