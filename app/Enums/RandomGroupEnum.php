<?php

namespace App\Enums;

enum RandomGroupEnum: string
{
    case BACKGROUND_SCENE = 'background_scene';
    case HUMAN_POSE = 'human_pose';
    case EXPRESSION = 'expression';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function strings(): string
    {
        return implode(',', self::values());
    }
}
