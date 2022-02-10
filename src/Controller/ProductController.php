<?php

namespace App\Controller;

use App\Entity\Pagination;
use App\Entity\Product;
use App\Form\PaginationType;
use App\Repository\ProductRepository;
use App\Service\FormHandler;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/product')]
class ProductController extends AController
{
    private FormHandler $formHandler;

    public function __construct(FormHandler $formHandler, ?SerializerInterface $serializer)
    {
        $this->formHandler = $formHandler;
        parent::__construct($serializer);
    }

    /**
     * List the rewards of the specified user.
     *
     * This call takes into account all confirmed awards, but not pending or refused awards.
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns all the product of an user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Product::class, groups={"product:list"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="order",
     *     in="query",
     *     description="The field used to order rewards",
     *     @OA\Schema(type="string")
     * )
     * @OA\Tag(name="products")
     * @Security(name="Bearer")
     */
    #[Route('', name: 'product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        $pagination = $this->formHandler->handle(PaginationType::class, null, 'query');

        return $this->json(
            $productRepository->findAllPaginated($pagination['page'], $pagination['nbElementsPerPage']),
            Response::HTTP_OK,
            [],
            [
                'groups' => [
                    'read:all',
                    'product:list'
                ]
            ]
        );
    }

    #[Route('/{id}', name: 'product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->json($product,
            Response::HTTP_OK,
            [],
            ['groups' => 'product:show']
        );
    }
}
