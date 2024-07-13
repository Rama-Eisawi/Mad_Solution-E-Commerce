<?php

namespace App\Traits;

trait ResponsesTrait
{
    public function showData($data, $code = 200)
    {
        return response()->json([
            'data' => $data,
        ], $code);
    }
    public function sendSuccess($data, $message, $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
    public function sendSuccessWithToken($data, $message, $code = 200,$accessToken,$refreshToken)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
            'access token'=>$accessToken,
            'refresh token'=>$refreshToken
        ], $code);
    }
    public function sendFail($message = 'Request fails', $code = 422)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], $code);
    }
}
