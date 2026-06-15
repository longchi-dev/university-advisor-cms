<?php

namespace App\Enums;

enum AILogStatusEnum: string
{
    case PENDING = 'pending';
    case SUCCESS = 'success';
    case ERROR = 'error';
}
