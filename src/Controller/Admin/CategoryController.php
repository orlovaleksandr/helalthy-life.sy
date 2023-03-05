<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\DTO\EditCategoryDto;
use App\Form\EditCategoryFormType;
use App\Form\Handler\CategoryFormHandler;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category', name: 'admin_category_')]
class CategoryController extends AbstractController
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        $categories = $this->categoryRepository->findBy(['isDeleted' => false], ['id' => 'DESC']);

        return $this->render('admin/category/list.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    #[Route('/add', name: 'add')]
    public function edit(Request $request, CategoryFormHandler $categoryFormHandler, Category $category = null): Response
    {
        $categoryModel = EditCategoryDto::createFromCategory($category);

        $form = $this->createForm(EditCategoryFormType::class, $categoryModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $categoryFormHandler->processEditForm($categoryModel);

            return $this->redirectToRoute('admin_category_edit', ['id' => $category->getId()]);
        }

        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);

    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Category $category): Response
    {
        $this->categoryRepository->removeWithoutDeleting($category);

        $this->addFlash(type: 'warning', message: 'The category was successful deleted!');

        return $this->redirectToRoute('admin_category_list');
    }
}
