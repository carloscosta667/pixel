<?php

namespace app\Traits;

trait Helper
{
    /**
     * The format response
     *
     * @param $data
     * @param $message
     * @return array
     */
    public function responseFormat($data, $message): array
    {
        return [
            'data' => $data,
            'message' => $message
        ];
    }
}
