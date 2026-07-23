<?php

namespace App\Contracts\Repositories;

use App\Enums\AIPromptTemplateEnum;

interface IPromptRepository
{
    public function getSystemPrompt(AIPromptTemplateEnum $aiPromptTemplate): string;
}
