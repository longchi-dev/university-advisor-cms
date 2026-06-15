<?php

namespace App\Enums;

enum RoleEnum: string
{
    case USER = 'user';
    case ASSISTANT = 'assistant';

    static public function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
