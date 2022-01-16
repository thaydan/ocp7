<?php

namespace App\Controller;

use App\Entity\Client;
use App\Exception\FormErrorException;
use App\Exception\PaginationErrorException;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Service\FormHandler;
use App\Service\PaginationHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/client')]
class ClientController extends AbstractController
{
    private FormHandler $formHandler;
    private PaginationHandler $paginationHandler;

    public function __construct(FormHandler $formHandler, PaginationHandler $paginationHandler)
    {
        $this->formHandler = $formHandler;
        $this->paginationHandler = $paginationHandler;
    }

    /**
     * @throws PaginationErrorException
     */
    #[Route('', name: 'client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {
        $pagination = $this->paginationHandler->handle();

        return $this->json(
            $clientRepository->findAllPaginated($pagination['page'] ?? 0, $pagination['nbElementsPerPage'] ?? 20),
            Response::HTTP_OK,
            [],
            [
                'groups' => [
                    'read:all',
                    'client:list'
                ]
            ]
        );
    }

    #[Route('/{id}', name: 'client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->json($client,
            Response::HTTP_OK,
            [],
            ['groups' => 'client:show']
        );
    }

    /**
     * @throws PaginationErrorException
     * @throws FormErrorException
     */
    #[Route('/{id}', name: 'client_edit', methods: ['PUT'])]
    public function edit(Client $client, EntityManagerInterface $entityManager): Response
    {
        $client = $this->formHandler->handle(ClientType::class, $client);

        $entityManager->flush();

        return $this->json(
            $client,
            Response::HTTP_OK,
            [],
            ['groups' => 'product:show']
        );
    }

    /**
     * @throws PaginationErrorException
     * @throws FormErrorException
     */
    #[Route('', name: 'client_new', methods: ['POST'])]
    public function new(EntityManagerInterface $entityManager): Response
    {
        $client = $this->formHandler->handle(ClientType::class, new Client());

        $entityManager->persist($client);
        $entityManager->flush();

        return $this->json(
            $client,
            Response::HTTP_CREATED,
            [],
            ['groups' => 'book:show']
        );
    }

    #[Route('/{id}', name: 'client_delete', methods: ['DELETE'])]
    public function delete(Client $client, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($client);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
