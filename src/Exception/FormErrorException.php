<?php

namespace App\Exception;

use Exception;
use Symfony\Component\Form\FormInterface;
use Throwable;

class FormErrorException extends Exception
{
//    private FormInterface $form;
//
//    public function __construct(FormInterface $form, string $message = "", int $code = 0, ?Throwable $previous = null)
//    {
//        parent::__construct($message, $code, $previous);
//        $this->form = $form;
//    }
//
//    /**
//     * @return FormInterface
//     */
//    public function getForm(): FormInterface
//    {
//        return $this->form;
//    }
}