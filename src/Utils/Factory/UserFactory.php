<?php

namespace App\Utils\Factory;

use App\Entity\User;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Provider\GoogleUser;

class UserFactory
{
    public static function createUserFromFacebookUser(FacebookUser $facebookUser): User
    {
        $user = new User();
        $user->setEmail($facebookUser->getEmail());
        $user->setFullName($facebookUser->getName());
        $user->setFacebookId($facebookUser->getId());

        return $user;
    }

    public static function createUserFromGoogleUser(GoogleUser $googleUser): User
    {
        $user = new User();
        $user->setEmail($googleUser->getEmail());
        $user->setFullName($googleUser->getName());
        $user->setGoogleId($googleUser->getId());

        return $user;
    }
}