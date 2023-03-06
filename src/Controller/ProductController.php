<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product/{uuid}', name: 'main_product')]
    public function index(Product $product = null): Response
    {
        if (!$product) {
            throw new NotFoundHttpException();
        }

        return $this->render('main/product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
