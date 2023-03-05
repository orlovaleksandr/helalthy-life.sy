<?php

namespace App\Form\Handler;

use App\Entity\Product;
use App\Form\DTO\EditProductDto;
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

    public function processEditForm(EditProductDto $productDto, Form $form): Product|null
    {
        $product = new Product();

        if ($productDto->id) {
            $product = $this->productRepository->find($productDto->id);
        }

        $product->setTitle($productDto->title);
        $product->setPrice($productDto->price);
        $product->setQuantity($productDto->quantity);
        $product->setDescription($productDto->description);
        $product->setCategory($productDto->category);
        $product->setIsPublished($productDto->isPublished);
        $product->setIsDeleted($productDto->isDeleted);

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