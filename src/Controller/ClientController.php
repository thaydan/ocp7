<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Form\PaginationType;
use App\Repository\ClientRepository;
use App\Service\FormHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/client')]
class ClientController extends AbstractController
{
    private FormHandler $formHandler;

    public function __construct(FormHandler $formHandler)
    {
        $this->formHandler = $formHandler;
    }

    #[Route('', name: 'client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository, Request $request): Response
    {
        $paginationForm = $this->createForm(PaginationType::class);
        $paginationForm->submit($request->query->all());

        if (!($paginationForm->isSubmitted() && $paginationForm->isValid())) {
            return $this->json('Bad Request', Response::HTTP_BAD_REQUEST);
        }
        $pagination = $paginationForm->getData();

        return $this->json($clientRepository->findAll(),
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
