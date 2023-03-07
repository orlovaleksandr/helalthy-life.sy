<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\Admin\EditProductFormType;
use App\Form\DTO\EditProductDto;
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
        $productModel = EditProductDto::makeFromProduct($product);

        $form = $this->createForm(EditProductFormType::class, $productModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $productFormHandler->processEditForm($productModel, $form);

            $this->addFlash(type: 'success', message: 'Your changes ware saved!');

            return $this->redirectToRoute('admin_product_edit', ['id' => $product->getId()]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash(type: 'warning', message: 'Something went wrong! Please check you form!');
        }

        $images = $product ? $product->getProductImages()->getValues() : [];


        return $this->render('admin/product/edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
            'images' => $images
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Product $product): Response
    {
        $this->productRepository->setIsDeleted($product, true);

        $this->addFlash(type: 'warning', message: 'The product was successful deleted!');

        return $this->redirectToRoute('admin_product_list');
    }
}
