<?php

namespace App\Controller;

use App\Entity\ClientCustomer;
use App\Entity\Pagination;
use App\Exception\FormErrorException;
use App\Exception\JsonInvalidException;
use App\Form\ClientCustomerType;
use App\Form\PaginationType;
use App\Repository\ClientCustomerRepository;
use App\Service\FormHandler;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/customer')]
class ClientCustomerController extends AController
{
    private FormHandler $formHandler;

    public function __construct(FormHandler $formHandler, SerializerInterface $serializer)
    {
        $this->formHandler = $formHandler;
        parent::__construct($serializer);
    }

    /**
     * @throws FormErrorException
     * @throws JsonInvalidException
     */
    #[Route('', name: 'client_customer_index', methods: ['GET'])]
    public function index(ClientCustomerRepository $clientCustomerRepository): Response
    {
        $pagination = $this->formHandler->handle(PaginationType::class, null, 'query');

        return $this->json(
            $clientCustomerRepository->findAllPaginated($pagination['page'], $pagination['nbElementsPerPage']),
            Response::HTTP_OK,
            [],
            [
                'groups' => [
                    'read:all',
                    'customer:list'
                ]
            ]
        );
    }

    #[Route('/{id}', name: 'client_customer_show', methods: ['GET'])]
    public function show(ClientCustomer $clientCustomer): Response
    {
        return $this->json($clientCustomer,
            Response::HTTP_OK,
            [],
            ['groups' => 'customer:show']
        );
    }

    /**
     * @throws FormErrorException
     * @throws JsonInvalidException
     */
    #[Route('/{id}', name: 'client_customer_edit', methods: ['PUT'])]
    public function edit(ClientCustomer $clientCustomer, EntityManagerInterface $entityManager): Response
    {
        $clientCustomer = $this->formHandler->handle(ClientCustomerType::class, $clientCustomer, 'json');

        $entityManager->flush();

        return $this->json(
            $clientCustomer,
            Response::HTTP_OK,
            [],
            ['groups' => 'customer:show']
        );
    }

    /**
     * @throws FormErrorException
     * @throws JsonInvalidException
     */
    #[Route('', name: 'client_customer_new', methods: ['POST'])]
    public function new(EntityManagerInterface $entityManager): Response
    {
        $clientCustomer = $this->formHandler->handle(ClientCustomerType::class, new ClientCustomer(), 'json');

        $entityManager->persist($clientCustomer);
        $entityManager->flush();

        return $this->json(
            $clientCustomer,
            Response::HTTP_CREATED,
            [],
            ['groups' => 'customer:show']
        );
    }

    #[Route('/{id}', name: 'client_customer_delete', methods: ['DELETE'])]
    public function delete(ClientCustomer $clientCustomer, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($clientCustomer);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
