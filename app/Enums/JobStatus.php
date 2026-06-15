<?php

namespace App\Enums;

enum JobStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case FAILED = 'failed';

    static public function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
