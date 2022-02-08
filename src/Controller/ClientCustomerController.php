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
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/customer')]
class ClientCustomerController extends AController
{
    private FormHandler $formHandler;
    private FormPaginationHandler $paginationHandler;
    private SerializerInterface $serializer;

    public function __construct(
        FormHandler $formHandler,
        FormPaginationHandler $paginationHandler,
        SerializerInterface $serializer
    )
    {
        $this->formHandler = $formHandler;
        $this->paginationHandler = $paginationHandler;
        $this->serializer = $serializer;
    }

    /**
     * @throws FormPaginationErrorException
     */
    #[Route('', name: 'client_customer_index', methods: ['GET'])]
    public function index(ClientCustomerRepository $clientCustomerRepository): Response
    {
        $pagination = $this->paginationHandler->handle();
        $customersPaginated = $clientCustomerRepository->findAllPaginated($pagination['page'] ?? 0, $pagination['nbElementsPerPage'] ?? 20);

        $data = $this->serializer->serialize($customersPaginated, 'json', SerializationContext::create()->setGroups(['read:all', 'customer:list']));

        $response = (new Response($data))->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

//        return $this->json(
//            $clientCustomerRepository->findAllPaginated($pagination['page'] ?? 0, $pagination['nbElementsPerPage'] ?? 20),
//            Response::HTTP_OK,
//            [],
//            [
//                'groups' => [
//                    'read:all',
//                    'customer:list'
//                ]
//            ]
//        );
    }

    #[Route('/{id}', name: 'client_customer_show', methods: ['GET'])]
    public function show(ClientCustomer $clientCustomer): Response
    {
        $data = $this->serializer->serialize($clientCustomer, 'json', SerializationContext::create()->setGroups(['customer:show']));

        $response = (new Response($data))->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

//        return $this->json($clientCustomer,
//            Response::HTTP_OK,
//            [],
//            ['groups' => 'customer:show']
//        );
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
