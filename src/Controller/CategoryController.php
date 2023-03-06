<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    #[Route('/category/{slug}', name: 'main_category')]
    public function show(Category $category = null): Response
    {
        if (!$category) {
            throw new NotFoundHttpException();
        }

        $products = $this->productRepository->findBy(['isPublished' => true, 'category' => $category->getId()]);

        return $this->render('main/category/show.html.twig', [
            'category' => $category,
            'products' => $products
        ]);
    }
}
