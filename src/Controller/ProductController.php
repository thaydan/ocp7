<?php

namespace App\Controller;

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

    public function __construct(FormHandler $formHandler, SerializerInterface $serializer)
    {
        $this->formHandler = $formHandler;
        parent::__construct($serializer);
    }

    /**
     * Get the list of the available products
     *
     * @OA\Response(
     *     response=200,
     *     description="Return the list of products available",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Product::class, groups={"read:all", "product:list"}))
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
     *     description="The number of products to show per page (min=5, max=40)",
     *     @OA\Schema(type="integer", minimum="5", maximum="40")
     * )
     * @OA\Tag(name="Products")
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

    /**
     * Get the details of a product
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns the details of a product",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Product::class, groups={"product:show"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id of the product",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Tag(name="Products")
     */
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
