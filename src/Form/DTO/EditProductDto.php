<?php

namespace App\Form\DTO;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class EditProductDto
{
    public ?int $id = null;

    #[Assert\NotBlank(message: 'Please enter a title')]
    public string $title;

    #[Assert\NotBlank(message: 'Please enter a price')]
    #[Assert\GreaterThanOrEqual(value: 0)]
    public float $price;

    #[Assert\File(
        maxSize: '5024k',
        mimeTypes: ['image/jpeg', 'image/png'],
        mimeTypesMessage: 'Please upload a valid image'
    )]
    public UploadedFile|null $newImage;

    #[Assert\NotBlank(message: 'Please enter a quantity')]
    #[Assert\GreaterThanOrEqual(value: 0)]
    public int $quantity;
    public string|null $description;

    #[Assert\NotBlank(message: 'Please select a category')]
    public Category $category;

    public bool $isPublished;
    public bool $isDeleted;

    public static function makeFromProduct(?Product $product): self
    {
        $model = new self();
        if (!$product) {
            return $model;
        }

        $model->id = $product->getId();
        $model->title = $product->getTitle();
        $model->price = $product->getPrice();
        $model->quantity = $product->getQuantity();
        $model->description = $product->getDescription();
        $model->isPublished = $product->isIsPublished();
        $model->isDeleted = $product->isIsDeleted();

        return $model;
    }
}