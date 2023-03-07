<?php

namespace App\Enums;

enum OrderStatus: int
{
    case CREATED = 0;
    case PROCESSED = 1;
    case COMPLETED = 2;
    case DELIVERED = 3;
    case DENIED = 4;

    public static function getStringCases(): array
    {
        return [
            self::CREATED->value => 'Created',
            self::PROCESSED->value => 'Processed',
            self::COMPLETED->value => 'Completed',
            self::DELIVERED->value => 'Delivered',
            self::DENIED->value => 'Denied',
        ];
    }

    public static function qwe()
    {
        return '123';
    }
}
