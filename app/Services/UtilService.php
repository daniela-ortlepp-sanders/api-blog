<?php

namespace App\Services;

use Illuminate\Support\Str;
use Concerns\InteractsWithInput;

class UtilService
{
    public function __construct() {}

    public function jsonResponse($status, $message, $data, $code) {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Get the bearer token from the request headers.
     *
     * @return string|null
    */
    public function bearerToken($request)
    {
        $header = $request->header('Authorization');
        if (Str::startsWith($header, 'Bearer ')) {
            return Str::substr($header, 7);
        } else {
            return $header;
        }
    }

}
