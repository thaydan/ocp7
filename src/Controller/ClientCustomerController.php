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
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[Route('/api/customer')]
class ClientCustomerController extends AController
{
    private FormHandler $formHandler;
    private TagAwareCacheInterface $cache;

    public function __construct(FormHandler $formHandler, SerializerInterface $serializer, TagAwareCacheInterface $cache)
    {
        $this->formHandler = $formHandler;
        $this->cache = $cache;
        parent::__construct($serializer, $cache);
    }

    /**
     * Get the list of your customers
     *
     * @OA\Tag(name="Customers")
     *
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The page number (optional)",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="nbElementsPerPage",
     *     in="query",
     *     description="The number of customers to show per page (optional, min=5, max=40)",
     *     @OA\Schema(type="integer", minimum=5, maximum=40)
     * )
     *
     * @OA\Response(
     *     response=200,
     *     description="Return the list of your customers",
     *     @OA\JsonContent(
     *         @OA\Property(
     *             property="elements",
     *             type="array",
     *             @OA\Items(ref=@Model(type=ClientCustomer::class, groups={"read:all", "customer:list"}))
     *         ),
     *         @OA\Property(
     *             property="nbPage",
     *             type="integer"
     *         ),
     *         @OA\Property(
     *             property="nbElements",
     *             type="integer"
     *         ),
     *         @OA\Property(
     *             property="nbElementsPerPage",
     *             type="integer"
     *         )
     *     )
     * )
     *
     * @throws FormErrorException
     * @throws JsonInvalidException
     */
    #[Route('', name: 'client_customer_index', methods: ['GET'])]
    public function index(ClientCustomerRepository $customerRepository): Response
    {
        $pagination = $this->formHandler->handle(PaginationType::class, null, 'query');

        $paginatedCustomers = $customerRepository->findAllPaginated($pagination['page'], $pagination['nbElementsPerPage'], ['client' => $this->getUser()]);
        return $this->json(
            $this->cacheHandle('customer.list.'. $pagination['page'], $paginatedCustomers, ['customer_list']),
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
    #[Route('/{id}', name: 'client_customer_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(ClientCustomer $clientCustomer): Response
    {
        $this->denyAccessUnlessIsOwner($clientCustomer);

        return $this->json(
            $this->cacheHandle('customer.show.'. $clientCustomer->getId(), $clientCustomer),
            Response::HTTP_OK,
            [],
            ['groups' => 'customer:show']
        );
    }

    /**
     * Edit a customer
     *
     * @OA\Tag(name="Customers")
     *
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id of the customer",
     *     @OA\Schema(type="integer")
     * )
     *
     * @OA\RequestBody(@Model(type=ClientCustomerType::class))
     * @OA\Response(
     *     response=200,
     *     description="Return the edited customer",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ClientCustomer::class, groups={"customer:show"}))
     *     )
     * )
     *
     * @throws FormErrorException
     * @throws JsonInvalidException
     */
    #[Route('/{id}', name: 'client_customer_edit', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function edit(ClientCustomer $clientCustomer, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessIsOwner($clientCustomer);

        $clientCustomer = $this->formHandler->handle(ClientCustomerType::class, $clientCustomer, 'json');
        $entityManager->flush();

        $this->cache->invalidateTags(['customer_list']);
        $this->cache->delete('customer.show.'. $clientCustomer->getId());

        return $this->json(
            $this->cacheHandle('customer.show.'. $clientCustomer->getId(), $clientCustomer),
            Response::HTTP_OK,
            [],
            ['groups' => 'customer:show']
        );
    }

    /**
     * Add a new customer
     *
     * @OA\Tag(name="Customers")
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id of the customer",
     *     @OA\Schema(type="integer")
     * )
     * @OA\RequestBody(@Model(type=ClientCustomerType::class))
     * @OA\Response(
     *     response=200,
     *     description="Return new added customer",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ClientCustomer::class, groups={"customer:show"}))
     *     )
     * )
     *
     * @throws FormErrorException
     * @throws JsonInvalidException
     */
    #[Route('', name: 'client_customer_new', methods: ['POST'])]
    public function new(EntityManagerInterface $entityManager): Response
    {
        $clientCustomer = $this->formHandler->handle(ClientCustomerType::class, new ClientCustomer(), 'json');
        $clientCustomer->setClient($this->getUser());

        $entityManager->persist($clientCustomer);
        $entityManager->flush();

        $this->cache->invalidateTags(['customer_list']);

        return $this->json(
            $this->cacheHandle('customer.show.'. $clientCustomer->getId(), $clientCustomer),
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
     *        type="string", nullable=true, example=""
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
    #[Route('/{id}', name: 'client_customer_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(ClientCustomer $clientCustomer, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessIsOwner($clientCustomer);

        $this->cache->invalidateTags(['customer_list']);
        $this->cache->delete('customer.show.'. $clientCustomer->getId());

        $entityManager->remove($clientCustomer);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    private function denyAccessUnlessIsOwner($clientCustomer): void
    {
        if ($clientCustomer->getClient() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
    }
}
