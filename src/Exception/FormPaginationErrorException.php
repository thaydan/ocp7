<?php

namespace App\Exception;

use Symfony\Component\Form\FormInterface;
use Throwable;

class FormPaginationErrorException extends FormErrorException
{
    public function __construct(FormInterface $form, string $message = "Pagination Error", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($form, $message, $code, $previous);
    }
}