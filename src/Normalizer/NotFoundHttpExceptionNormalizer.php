<?php

namespace App\Normalizer;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotFoundHttpExceptionNormalizer extends ANormalizer
{
    protected function getAcceptedException(): string
    {
        return NotFoundHttpException::class;
    }

    public function normalize(Exception $exception): Response
    {
        if (!$exception instanceof NotFoundHttpException) {
            throw new Exception('Bad exception');
        }

        return new JsonResponse(
            ['message' => 'Not found'],
            Response::HTTP_BAD_REQUEST
        );
    }
}