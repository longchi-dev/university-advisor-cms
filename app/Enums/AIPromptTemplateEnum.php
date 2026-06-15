<?php

namespace App\Enums;

enum AIPromptTemplateEnum: string
{
    case CLASSIFY_INTENT = 'classify_intent';
    case UNIVERSITY_ADVISOR = 'university_advisor';
    case EXTRACT_METADATA_QUERY = 'extract_metadata_query';
    case REWRITE_QUERY = 'rewrite_query';
    case VERIFY_ANSWER = 'verify_answer';
}
