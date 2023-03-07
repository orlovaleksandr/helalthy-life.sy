<?php

namespace App\Controller\Main;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private ProductRepository $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    #[Route(path: '/', name: 'homePage')]
    public function index(): Response
    {
        $products = $this->productRepository->findAll();

        return $this->render('main/default/index.html.twig', [
            'products' => $products,
        ]);
    }
}
