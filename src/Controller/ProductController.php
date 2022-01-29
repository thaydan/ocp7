<?php

namespace App\Controller;

use App\Entity\Product;
use App\Exception\FormPaginationErrorException;
use App\Repository\ProductRepository;
use App\Service\FormPaginationHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/product')]
class ProductController extends AbstractController
{
    private FormPaginationHandler $paginationHandler;

    public function __construct(FormPaginationHandler $paginationHandler)
    {
        $this->paginationHandler = $paginationHandler;
    }

    /**
     * @throws FormPaginationErrorException
     */
    #[Route('', name: 'product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        $pagination = $this->paginationHandler->handle();

        return $this->json(
            $productRepository->findAllPaginated($pagination['page'] ?? 0, $pagination['nbElementsPerPage'] ?? 20),
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
