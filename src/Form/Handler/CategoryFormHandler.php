<?php

namespace App\Form\Handler;

use App\Entity\Category;
use App\Form\DTO\EditCategoryDto;
use App\Repository\CategoryRepository;

class CategoryFormHandler
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function processEditForm(EditCategoryDto $categoryModel): Category
    {
        $category = new Category();

        if ($categoryModel->id) {
            $category = $this->categoryRepository->find($categoryModel->id);
        }

        $category->setTitle($categoryModel->title);

        $this->categoryRepository->save($category, true);

        return $category;
    }
}