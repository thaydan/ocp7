<?php

namespace App\Controller;

use App\Entity\Product;
use App\Exception\FormErrorException;
use App\Exception\PaginationErrorException;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\FormHandler;
use App\Service\PaginationHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/product')]
class ProductController extends AbstractController
{
    private FormHandler $formHandler;
    private PaginationHandler $paginationHandler;

    public function __construct(FormHandler $formHandler, PaginationHandler $paginationHandler)
    {
        $this->formHandler = $formHandler;
        $this->paginationHandler = $paginationHandler;
    }

    /**
     * @throws PaginationErrorException
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

    /**
     * @throws PaginationErrorException
     * @throws FormErrorException
     */
    #[Route('/{id}', name: 'product_edit', methods: ['PUT'])]
    public function edit(Product $product, EntityManagerInterface $entityManager): Response
    {
        $product = $this->formHandler->handle(ProductType::class, $product);

        $entityManager->flush();

        return $this->json(
            $product,
            Response::HTTP_OK,
            [],
            ['groups' => 'product:show']
        );
    }

    /**
     * @throws PaginationErrorException
     * @throws FormErrorException
     */
    #[Route('', name: 'product_new', methods: ['POST'])]
    public function new(EntityManagerInterface $entityManager): Response
    {
        $product = $this->formHandler->handle(ProductType::class, new Product());

        $entityManager->persist($product);
        $entityManager->flush();

        return $this->json(
            $product,
            Response::HTTP_CREATED,
            [],
            ['groups' => 'product:show']
        );
    }

    #[Route('/{id}', name: 'product_delete', methods: ['DELETE'])]
    public function delete(Product $product, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
