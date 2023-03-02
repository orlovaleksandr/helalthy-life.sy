<?php

namespace App\Form\Handler;

use App\Entity\Product;
use App\Repository\ProductImageRepository;
use App\Repository\ProductRepository;
use App\Service\FileServices\FileSaver;
use Symfony\Component\Form\Form;

class ProductFormHandler
{
    private ProductRepository $productRepository;
    private ProductImageRepository $productImageRepository;
    private FileSaver $fileSaver;

    public function __construct(
        ProductRepository $productRepository,
        ProductImageRepository $productImageRepository,
        FileSaver $fileSaver
    )
    {
        $this->productRepository = $productRepository;
        $this->productImageRepository = $productImageRepository;
        $this->fileSaver = $fileSaver;
    }

    public function processEditForm(Product $product, Form $form): Product
    {
        // TODO: Add a new images with different sizes to prouct
        $this->productRepository->save($product, true);

        $newImageFile = $form->get('newImage')->getData();
        $tempImageFilename = $newImageFile
            ? $this->fileSaver->saveUploadedFileIntoTemp($newImageFile)
            : null;

        $this->productRepository->uploadProductImages($product, $tempImageFilename);

        $this->productRepository->save($product, true);

        return $product;
    }
}