<?php

namespace App\Enums;

enum UserIntentEnum: string
{
    case GREETING = 'greeting';

    case ADVISE = 'advise';

    case MBTI = 'mbti';

    case IRRELEVANT = 'irrelevant';

    case BLOCKED = 'blocked';
}
