<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\PaginationType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\FormHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/product')]
class ProductController extends AbstractController
{
    private FormHandler $formHandler;

    public function __construct(FormHandler $formHandler)
    {
        $this->formHandler = $formHandler;
    }

    #[Route('', name: 'product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository, Request $request): Response
    {
        $paginationForm = $this->createForm(PaginationType::class);
        $paginationForm->submit($request->query->all());

        if (!($paginationForm->isSubmitted() && $paginationForm->isValid())) {
            return $this->json('Bad Request', Response::HTTP_BAD_REQUEST);
        }
        $pagination = $paginationForm->getData();
        //dd($pagination);

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

    #[Route('', name: 'product_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
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
