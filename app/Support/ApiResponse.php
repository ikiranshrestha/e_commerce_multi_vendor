<?php

namespace App\Support;

class ApiResponse
{
    public static function success($data = null, string $message = 'Success', int $code = 200)
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    public static function error(string $message, int $code = 400, $errors = null)
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }

    public static function exception(string $message, $trace = null, int $code = 500)
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'trace'   => config('app.debug') ? $trace : null,
        ], $code);
    }
}
