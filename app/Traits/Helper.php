<?php

namespace app\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

trait Helper
{
    /**
     * The format response
     *
     * @param $data
     * @param $message
     * @param $status_code
     * @return JsonResponse
     */
    public function responseFormat($data, $message, $status_code): JsonResponse
    {
        return response()->json(
            [
            'data' => $data,
            'message' => $message
            ],
            $status_code
        );
    }

    /**
     * The format response for unknown error
     *
     * @param $exception
     * @return JsonResponse
     */
    public function responseFormatUnknown($exception): JsonResponse
    {
        //log any critical issue to be easier to debug
        Log::critical($exception->getMessage());
        return response()->json(
            [
                'message' => config('api.something_wrong')
            ]
            , Response::HTTP_BAD_GATEWAY);
    }

}
