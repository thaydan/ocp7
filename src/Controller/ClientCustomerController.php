<?php

namespace App\Controller;

use App\Entity\ClientCustomer;
use App\Form\ClientCustomerType;
use App\Repository\ClientCustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/customer')]
class ClientCustomerController extends AbstractController
{
    #[Route('', name: 'client_customer_index', methods: ['GET'])]
    public function index(ClientCustomerRepository $clientCustomerRepository): Response
    {
        return $this->json($clientCustomerRepository->findAll(),
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

    #[Route('/{id}/edit', name: 'client_customer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ClientCustomer $clientCustomer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClientCustomerType::class, $clientCustomer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('client_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client_customer/edit.html.twig', [
            'client_customer' => $clientCustomer,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'client_customer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $clientCustomer = new ClientCustomer();
        $form = $this->createForm(ClientCustomerType::class, $clientCustomer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($clientCustomer);
            $entityManager->flush();

            return $this->redirectToRoute('client_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client_customer/new.html.twig', [
            'client_customer' => $clientCustomer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'client_customer_delete', methods: ['POST'])]
    public function delete(Request $request, ClientCustomer $clientCustomer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clientCustomer->getId(), $request->request->get('_token'))) {
            $entityManager->remove($clientCustomer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('client_customer_index', [], Response::HTTP_SEE_OTHER);
    }
}
