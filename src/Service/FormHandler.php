<?php

namespace App\Service;

use App\Exception\FormErrorException;
use App\Exception\JsonInvalidException;
use Psr\Log\InvalidArgumentException;
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
     */
    public function handle(string $formTypeClass, mixed $object, string $type): mixed
    {
        $form = $this->formFactory->create($formTypeClass, $object);

        switch ($type) {
            case 'json':
                $data = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);
                if (json_last_error()) {
                    throw new JsonInvalidException();
                }
                break;
            case 'query':       // GET
                $data = $this->requestStack->getCurrentRequest()->query->all();
                break;
            case 'request':     // POST
                $data = $this->requestStack->getCurrentRequest()->request->all();
                break;
            default:
                throw new InvalidArgumentException('FormHandler : Invalid data input type.');
        }

        $form->submit($data);

        if (!($form->isSubmitted() && $form->isValid())) {
            throw new FormErrorException($form);
        }

        return $form->getData();
    }
}