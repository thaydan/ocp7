<?php

namespace App\Service;

use App\Exception\FormErrorException;
use App\Exception\PaginationErrorException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class FormHandler
{

    private FormFactoryInterface $formFactory;
    private RequestStack $requestStack;

    public function __construct(FormFactoryInterface $formFactory, RequestStack $requestStack)
    {
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
    }

    /**
     * @throws PaginationErrorException
     * @throws FormErrorException
     */
    public function handle(string $formTypeClass, mixed $object): mixed
    {
        $form = $this->formFactory->create($formTypeClass, $object);
        $jsonDecode = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);

        if (json_last_error()) {
            throw new PaginationErrorException();
        }

        $form->submit($jsonDecode);

        if (!($form->isSubmitted() && $form->isValid())) {
            throw new FormErrorException($form);
        }

        return $form->getData();
    }
}