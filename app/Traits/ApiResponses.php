<?php

namespace App\Traits;
trait ApiResponses
{
    protected function ok($message, $data = [])
    {
        return $this->successResponse($message, $data, 200);
    }

    protected function successResponse($message, $data = [], $statusCode = 200)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }

    protected function error($message, $statusCode)
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }
}
