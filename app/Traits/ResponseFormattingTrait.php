<?php

namespace App\Traits;

trait ResponseFormattingTrait
{
    private function _formatCountResponse($data, $perPage, $total)
    {
        return [
            'content' => $data,
            'totalElements' => $perPage,
            'totalPages' => $total,
        ];
    }

    private function _formatBaseResponse($statusCode, $data, $message)
    {
        return [
            'statusCode' => $statusCode,
            'data' => $data,
            'message' => $message,
        ];
    }
    private function _formatBaseResponseWithTotal($statusCode, $data, $total, $message)
    {
        return [
            'statusCode' => $statusCode,
            'data' => $data,
            'total' => $total,
            'message' => $message,
        ];
    }
}
