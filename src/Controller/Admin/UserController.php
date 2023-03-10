<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Enums\UserRoles;
use App\Form\Admin\EditUserFormType;
use App\Form\Handler\UserFormHandler;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user', name: 'admin_user_')]
class UserController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        $users = $this->userRepository->findBy(['isDeleted' => false], ['id' => 'DESC']);

        return $this->render('admin/user/list.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    #[Route('/add', name: 'add')]
    public function edit(Request $request, UserFormHandler $userFormHandler, User $user = null): Response
    {
        if (!$user) {
            $user = new User();
        }

        $form = $this->createForm(EditUserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userFormHandler->processEditForm($form);
            $this->addFlash(type: 'success', message: 'Your changes ware saved!');

            return $this->redirectToRoute('admin_user_edit', ['id' => $user->getId()]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash(type: 'warning', message: 'Something went wrong! Please check you form!');
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(User $user): Response
    {
        $this->userRepository->removeWithoutDeleting($user);

        $this->addFlash(type: 'warning', message: 'The user was successful deleted!');

        return $this->redirectToRoute('admin_user_list');
    }
}
