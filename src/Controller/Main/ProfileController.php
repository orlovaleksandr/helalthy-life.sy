<?php

namespace App\Controller\Main;

use App\Form\Main\ProfileEditFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/profile', name: 'main_profile')]
    public function index(): Response
    {
        return $this->render('main/profile/index.html.twig');
    }

    #[Route('/profile/edit', name: 'main_profile-edit')]
    public function edit(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileEditFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->save($form->getData(), true);

            return $this->redirectToRoute('main_profile');
        }

        return $this->render('main/profile/edit.html.twig', [
            'profileEditForm' => $form->createView()
        ]);
    }
}
