<?php

namespace App\Contracts\Repositories;

use App\Enums\AIPromptTemplateEnum;
use App\Models\AI\AIPromptTemplate;

interface IAIPromptTemplateRepository
{
    public function findById(string $id): ?AIPromptTemplate;
    public function findByType(AIPromptTemplateEnum $aiPromptEnum): ?AIPromptTemplate;
    public function save(AIPromptTemplate $aiPrompt): AIPromptTemplate;
}
