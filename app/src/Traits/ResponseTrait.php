<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;

trait ResponseTrait
{

    /**
     * @param $data
     * @param $message
     * @param $status
     * @param $code
     * @return JsonResponse
     */
    public function prepareJsonResponse($data, string $message = "", bool $status = true, int $code = 8000): JsonResponse
    {
        return new JsonResponse(
            array(
                "data" => $data,
                "message" => $message,
                "status" => $status,
                "statusCode" => $code
            )
        );
    }
}