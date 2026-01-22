<?php

namespace App\Enums;

enum CommonStatusEnum: string
{
    case PENDING = 'PENDING';
    case PROCESSING = 'PROCESSING';
    case COMPLETED = 'COMPLETED';
    case FAILED = 'FAILED';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function strings(): string
    {
        return implode(',', self::values());
    }
}
