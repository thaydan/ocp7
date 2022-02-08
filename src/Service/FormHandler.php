<?php

namespace App\Service;

use App\Exception\FormErrorException;
use App\Exception\FormPaginationErrorException;
use App\Exception\JsonInvalidException;
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
     * @throws FormErrorException|JsonInvalidException
     */                                                 // ajouter type => json, request, query
    public function handle(string $formTypeClass, mixed $object): mixed
    {
        $form = $this->formFactory->create($formTypeClass, $object);
        $jsonDecode = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);

        if (json_last_error()) {
            throw new JsonInvalidException();
        }

        $form->submit($jsonDecode);

        if (!($form->isSubmitted() && $form->isValid())) {
            throw new FormErrorException($form);
        }

        return $form->getData();
    }
}