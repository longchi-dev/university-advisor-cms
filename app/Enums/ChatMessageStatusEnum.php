<?php

namespace App\Enums;

enum ChatMessageStatusEnum: string
{
    case CLASSIFYING = 'classifying';
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case SUCCESS = 'success';
    case FAILED = 'failed';
}
