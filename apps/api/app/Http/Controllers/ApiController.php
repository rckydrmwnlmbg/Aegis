<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class ApiController
{
    /**
     * Return a success response in Canonical JSON Envelope format.
     *
     * @param mixed $data
     * @param array $meta
     * @param int $status
     * @return JsonResponse
     */
    protected function respondSuccess(mixed $data = [], array $meta = [], int $status = 200): JsonResponse
    {
        $response = [
            'data' => empty($data) ? new \stdClass() : $data,
            'meta' => empty($meta) ? new \stdClass() : $meta,
        ];

        return response()->json($response, $status);
    }
}
