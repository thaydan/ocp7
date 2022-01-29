<?php

namespace App\Controller;

use App\Entity\ClientCustomer;
use App\Exception\FormErrorException;
use App\Exception\FormPaginationErrorException;
use App\Form\ClientCustomerType;
use App\Repository\ClientCustomerRepository;
use App\Service\FormHandler;
use App\Service\FormPaginationHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/customer')]
class ClientCustomerController extends AbstractController
{
    private FormHandler $formHandler;
    private FormPaginationHandler $paginationHandler;

    public function __construct(FormHandler $formHandler, FormPaginationHandler $paginationHandler)
    {
        $this->formHandler = $formHandler;
        $this->paginationHandler = $paginationHandler;
    }

    /**
     * @throws FormPaginationErrorException
     */
    #[Route('', name: 'client_customer_index', methods: ['GET'])]
    public function index(ClientCustomerRepository $clientCustomerRepository): Response
    {
        $pagination = $this->paginationHandler->handle();

        return $this->json(
            $clientCustomerRepository->findAllPaginated($pagination['page'] ?? 0, $pagination['nbElementsPerPage'] ?? 20),
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
     * @throws FormPaginationErrorException
     * @throws FormErrorException
     */
    #[Route('/{id}', name: 'client_customer_edit', methods: ['PUT'])]
    public function edit(ClientCustomer $clientCustomer, EntityManagerInterface $entityManager): Response
    {
        $clientCustomer = $this->formHandler->handle(ClientCustomerType::class, $clientCustomer);

        $entityManager->flush();

        return $this->json(
            $clientCustomer,
            Response::HTTP_OK,
            [],
            ['groups' => 'customer:show']
        );
    }

    /**
     * @throws FormPaginationErrorException
     * @throws FormErrorException
     */
    #[Route('', name: 'client_customer_new', methods: ['POST'])]
    public function new(EntityManagerInterface $entityManager): Response
    {
        $clientCustomer = $this->formHandler->handle(ClientCustomerType::class, new ClientCustomer());

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
