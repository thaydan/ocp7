<?php

namespace App\Controller;

use App\Entity\Product;
use App\Exception\FormPaginationErrorException;
use App\Repository\ProductRepository;
use App\Service\FormPaginationHandler;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

#[Route('/api/product')]
class ProductController extends AbstractController
{
    private FormPaginationHandler $paginationHandler;
    private SerializerInterface $serializer;

    public function __construct(FormPaginationHandler $paginationHandler, SerializerInterface $serializer)
    {
        $this->paginationHandler = $paginationHandler;
        $this->serializer = $serializer;
    }

    /**
     * @throws FormPaginationErrorException
     */
    #[Route('', name: 'product_index', methods: ['GET'])]
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
    public function index(ProductRepository $productRepository): Response
    {
        $pagination = $this->paginationHandler->handle();
        $productsPaginated = $productRepository->findAllPaginated($pagination['page'] ?? 0, $pagination['nbElementsPerPage'] ?? 20);

        $data = $this->serializer->serialize($productsPaginated, 'json', SerializationContext::create()->setGroups(['read:all', 'product:list']));

        $response = (new Response($data))->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

//        return $this->json(
//            $productRepository->findAllPaginated($pagination['page'] ?? 0, $pagination['nbElementsPerPage'] ?? 20),
//            Response::HTTP_OK,
//            [],
//            [
//                'groups' => [
//                    'read:all',
//                    'product:list'
//                ]
//            ]
//        );
    }

    #[Route('/{id}', name: 'product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        $data = $this->serializer->serialize($product, 'json', SerializationContext::create()->setGroups(['product:show']));

        $response = (new Response($data))->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

//        return $this->json($product,
//            Response::HTTP_OK,
//            [],
//            ['groups' => 'product:show']
//        );
    }
}
