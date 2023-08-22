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
    public function authorization(Request $request) {
        $whiteListIps = \Config::get('variables.white_list_ips');

        $ip = $request->get('ip');

        if (in_array($ip, $valid_ips)) {
            return response()->json([
                'status' => 'Authorized',
            ], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    /*public function getip(Request $request) {
        $valid_ips = \Config::get('variables.valid_ips');

        $ip = $request->get('ip');

        if (in_array($ip, $valid_ips)) {
            return response()->json([
                'status' => 'Authorized',
            ], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }*/

    public function login(Request $request)
    {
        $apiKey = $request->header('X-API-KEY');

        $requestTime = date('Y-m-d H:i:s', time());
        $data = ['api_key' => $apiKey, 'requestTime' => $requestTime];

        $currentDateTime = Carbon::now();
        $exp = Carbon::now()->addMinutes(60)->setTimezone('America/Sao_Paulo')->timestamp;

        $payload = [
            'iat' => time(),
            'exp' => $exp
        ];
        
        $customClaims = JWTFactory::customClaims($data);
        $payload = JWTFactory::make($data);

        $token = JWTAuth::encode($payload, $data);

        return response()->json([
            'status' => 'success',
            'authorization' => [
                'token' => $token->get(),
                'exp' => $exp,
                'type' => 'bearer',
            ]
        ]);
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
