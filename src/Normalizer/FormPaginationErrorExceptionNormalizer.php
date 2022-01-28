<?php

namespace App\Normalizer;

use App\Exception\FormErrorException;
use App\Exception\FormPaginationErrorException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FormPaginationErrorExceptionNormalizer extends FormErrorExceptionNormalizer
{
    protected function getAcceptedException(): string
    {
        return FormPaginationErrorException::class;
    }


    public function normalize(Exception $exception): Response
    {
        if (!$exception instanceof FormErrorException) {
            throw new Exception('Bad exception');
        }

        return new JsonResponse(
            [
                'message' => 'Pagination Error',
                'errors' => $this->serializeErrors($exception->getForm())
            ],
            Response::HTTP_BAD_REQUEST
        );
    }
}