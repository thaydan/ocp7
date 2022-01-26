<?php

namespace App\Normalizer;

use App\Exception\JsonInvalidException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class JsonInvalidExceptionNormalizer extends ANormalizer
{
    protected function getAcceptedException(): string
    {
        return JsonInvalidException::class;
    }

    public function normalize(Exception $exception): Response
    {
        if (!$exception instanceof JsonInvalidException) {
            throw new Exception('Bad exception');
        }

        return new JsonResponse(
            ['message' => 'Json Invalid'],
            Response::HTTP_BAD_REQUEST
        );
    }
}