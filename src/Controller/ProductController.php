<?php

namespace App\Controller;

use App\Entity\Product;
use App\Exception\FormPaginationErrorException;
use App\Repository\ProductRepository;
use App\Service\FormPaginationHandler;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
