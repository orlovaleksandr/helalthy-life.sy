<?php

namespace App\Controller\Admin;

use App\Entity\ProductImage;
use App\Repository\ProductImageRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product-image', name: 'admin_product_image_')]
class AdminProductImageController extends AbstractController
{
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(
        ProductRepository $productRepository,
        ProductImageRepository $productImageRepository,
        ProductImage $productImage = null
    ): Response
    {
        if (!$productImage) {
            return $this->redirectToRoute('admin_product_list');
        }

        $product = $productImage->getProduct();
        $productImageDir = $productRepository->getProductImagesDir($product);
        $productImageRepository->removeImageFromProduct($productImage, $productImageDir);

        return $this->redirectToRoute('admin_product_edit', [
            'id' => $product->getId()
        ]);
    }
}
