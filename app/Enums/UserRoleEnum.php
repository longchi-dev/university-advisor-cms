<?php

namespace App\Enums;

enum UserRoleEnum: string
{
    case VIEWER = 'VIEWER';
    case SETTING = 'SETTING';

    case ADMIN = 'ADMIN';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function strings(): string
    {
        return implode(',', self::values());
    }
}
