<?php

namespace App\Enums;

enum UserRoles: string
{
    case USER = 'ROLE_USER';
    case ADMIN = 'ROLE_ADMIN';
    case SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    public static function getStringCases(): array
    {
        return [
            self::USER->value => 'User',
            self::ADMIN->value => 'Admin',
            self::SUPER_ADMIN->value => 'Super Admin',
        ];
    }
}
