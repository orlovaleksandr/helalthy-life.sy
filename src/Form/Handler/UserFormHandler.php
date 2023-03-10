<?php

namespace App\Form\Handler;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\Form;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFormHandler
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function processEditForm(Form $form): User
    {
        /** @var User $user */
        $user = $form->getData();
        $plainPassword = $form->get('plainPassword')->getData();
        $newEmail =  $form->get('newEmail')->getData();
        $roles = $form->get('roles')->getData();

        if (!$user->getId()) {
            $user->setEmail($newEmail);
        }

        if ($plainPassword) {
            $encodedPassword = $this->userPasswordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($encodedPassword);
        }

        if ($roles) {
            $user->setRoles($roles);
        }

        $this->userRepository->save($user, true);

        return $user;
    }
}