<?php

namespace App\Normalizer;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HttpExceptionNormalizer extends ANormalizer
{
    protected function getAcceptedException(): string
    {
        return HttpException::class;
    }

    public function normalize(Exception $exception): Response
    {
        if (!$exception instanceof HttpException) {
            throw new Exception('Bad exception');
        }

        return new JsonResponse(
            [
                'message' => $exception->getMessage()
            ],
            $exception->getStatusCode(),
            $exception->getHeaders()
        );
    }
}