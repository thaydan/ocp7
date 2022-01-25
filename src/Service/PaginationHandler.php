<?php

namespace App\Service;

use App\Exception\PaginationErrorException;
use App\Form\PaginationType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginationHandler
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
     */
    public function handle(): mixed
    {
        $paginationForm = $this->formFactory->create(PaginationType::class);
        $paginationForm->submit($this->requestStack->getCurrentRequest()->query->all());

        if (!($paginationForm->isSubmitted() && $paginationForm->isValid())) {
            throw new PaginationErrorException();
        }

        return $paginationForm->getData();
    }
}