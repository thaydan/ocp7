<?php

namespace App\Controller;

use App\Entity\ClientCustomer;
use App\Exception\FormErrorException;
use App\Exception\JsonInvalidException;
use App\Form\ClientCustomerType;
use App\Form\PaginationType;
use App\Repository\ClientCustomerRepository;
use App\Service\FormHandler;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
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
     * Get the list of your customers
     *
     * @OA\Response(
     *     response=200,
     *     description="Return the list of your customers",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ClientCustomer::class, groups={"read:all", "customer:list"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The page number",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="nbElementsPerPage",
     *     in="query",
     *     description="The number of customers to show per page (min=5, max=40)",
     *     @OA\Schema(type="integer", minimum=5, maximum=40)
     * )
     * @OA\Tag(name="Customers")
     *
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


    /**
     * Show the details of a customer
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns the details of a customer",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ClientCustomer::class, groups={"customer:show"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id of the customer",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Tag(name="Customers")
     */
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
     * Edit a customer
     *
     * @OA\Response(
     *     response=200,
     *     description="Return the edited customer",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ClientCustomer::class, groups={"customer:show"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id of the customer",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Tag(name="Customers")
     *
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
     * Add a new customer
     *
     * @OA\Response(
     *     response=200,
     *     description="Return new added customer",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ClientCustomer::class, groups={"customer:show"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id of the customer",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Tag(name="Customers")
     *
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

    /**
     *
     * Delete a customer
     *
     * @OA\Response(
     *     response=204,
     *     description="Returns an empty response if the deletion was successful.",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ClientCustomer::class, groups={"customer:show"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id of the customer",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Tag(name="Customers")
     */
    #[Route('/{id}', name: 'client_customer_delete', methods: ['DELETE'])]
    public function delete(ClientCustomer $clientCustomer, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($clientCustomer);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
