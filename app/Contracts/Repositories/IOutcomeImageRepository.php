<?php

namespace App\Contracts\Repositories;

use App\Models\OutcomeImage;

interface IOutcomeImageRepository
{
    public function findById(int $id): ?OutcomeImage;

    public function findBySessionId(string $sessionId): ?OutcomeImage;

    public function save(OutcomeImage $outcomeImage): OutcomeImage;
}
