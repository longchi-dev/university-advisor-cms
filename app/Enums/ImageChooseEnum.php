<?php

namespace App\Enums;

enum ImageChooseEnum: string
{
    case IMAGE_1 = 'image_1';
    case IMAGE_2 = 'image_2';
    case IMAGE_3 = 'image_3';
    case IMAGE_4 = 'image_4';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function strings(): string
    {
        return implode(',', self::values());
    }
}
