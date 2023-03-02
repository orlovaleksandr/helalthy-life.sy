<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\EditProductFormType;
use App\Form\Handler\ProductFormHandler;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product', name: 'admin_product_')]
class ProductController extends AbstractController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        $products = $this->productRepository->findBy(
            ['isDeleted' => false],
            ['id' => 'DESC'],
            50
        );

        return $this->render('admin/product/list.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    #[Route('/add', name: 'add')]
    public function edit(Request $request, ProductFormHandler $productFormHandler, Product $product = null): Response
    {
        if (!$product) {
            $product = new Product();
        }

        $form = $this->createForm(EditProductFormType::class, $product);
        $form->handleRequest($request);

        if ($product && $form->isSubmitted() && $form->isValid()) {
            $product = $productFormHandler->processEditForm($product, $form);

            return $this->redirectToRoute('admin_product_edit', ['id' => $product->getId()]);
        }

        return $this->render('admin/product/edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
            'images' => $product->getProductImages()->getValues() ?? []
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Product $product): Response
    {
        $this->productRepository->setIsDeleted($product, true);

        return $this->redirectToRoute('admin_product_list');
    }
}
