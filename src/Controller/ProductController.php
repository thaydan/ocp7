<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\PaginationType;
use App\Repository\ProductRepository;
use App\Service\FormHandler;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[Route('/api/product')]
class ProductController extends AController
{
    private FormHandler $formHandler;

    public function __construct(FormHandler $formHandler, SerializerInterface $serializer, TagAwareCacheInterface $cache)
    {
        $this->formHandler = $formHandler;
        parent::__construct($serializer, $cache);
    }

    /**
     * Get the list of the available products
     *
     * @OA\Tag(name="Products")
     *
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The page number (optional, min=0)",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="nbElementsPerPage",
     *     in="query",
     *     description="The number of products to show per page (optional, min=5, max=40)",
     *     @OA\Schema(type="integer", minimum=5, maximum=40)
     * )
     *
     * @OA\Response(
     *     response=200,
     *     description="Return the list of available products",
     *     @OA\JsonContent(
     *         @OA\Property(
     *             property="elements",
     *             type="array",
     *             @OA\Items(ref=@Model(type=Product::class, groups={"read:all", "product:list"}))
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
     */
    #[Route('', name: 'product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        $pagination = $this->formHandler->handle(PaginationType::class, null, 'query');

        $paginatedProducts = $productRepository->findAllPaginated($pagination['page'], $pagination['nbElementsPerPage']);
        return $this->json(
            $this->cacheHandle('product.list.'. $pagination['page'], $paginatedProducts),
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
     * @OA\Tag(name="Products")
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id of the product",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Response(
     *     response=200,
     *     description="Returns the details of a product",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Product::class, groups={"product:show"}))
     *     )
     * )
     */
    #[Route('/{id}', name: 'product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->json(
            $this->cacheHandle('product.show.'. $product->getId(), $product),
            Response::HTTP_OK,
            [],
            ['groups' => 'product:show']
        );
    }
}
