<?php

namespace App\Normalizer;

use App\Exception\FormErrorException;
use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FormErrorExceptionNormalizer extends ANormalizer
{
    protected function getAcceptedException(): string
    {
        return FormErrorException::class;
    }

    public function normalize(Exception $exception): Response
    {
        if (!$exception instanceof FormErrorException) {
            throw new Exception('Bad exception');
        }

        return new JsonResponse(
            [
                'message' => 'Form Error',
                'errors' => $this->serializeErrors($exception->getForm())
            ],
            Response::HTTP_BAD_REQUEST
        );
    }

    private function serializeErrors(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors() as $formError) {
            $errors['globals'][] = $formError->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->subSerializeErrors($childForm)) {
                    $errors['fields'][$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }

    private function subSerializeErrors(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->subSerializeErrors($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }
}