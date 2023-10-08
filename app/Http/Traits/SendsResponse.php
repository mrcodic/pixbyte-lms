<?php

namespace App\Http\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait SendsResponse
{
    public function toResponse($request): Response
    {

        $data = [
            'code' => $this->code->value,
            'message' => __($this->message),
            'body' => (object) $this->body,
            'errors' => $this->errors,
        ];
        return new JsonResponse(
            data: $data,
            status: $this->code->value,
        );
    }
}
