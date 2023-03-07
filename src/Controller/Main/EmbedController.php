<?php

namespace App\Controller\Main;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class EmbedController extends AbstractController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function showSimilarProducts($limit = 2, int $categoryId = null): Response
    {
        $params = [];

        if ($categoryId) {
            $params['category'] = $categoryId;
        }

        $products = $this->productRepository->findBy($params, orderBy: ['id' => 'DESC'], limit: $limit);

        return $this->render('main/_embed/_similar_products.html.twig', [
            'products' => $products,
        ]);
    }
}
