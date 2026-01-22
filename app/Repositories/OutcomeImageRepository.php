<?php

namespace App\Repositories;

use App\Contracts\Repositories\IOutcomeImageRepository;
use App\Models\OutcomeImage;

class OutcomeImageRepository implements IOutcomeImageRepository
{
    public function findById(int $id): ?OutcomeImage
    {
        return OutcomeImage::query()->find($id);
    }

    public function findBySessionId(string $sessionId): ?OutcomeImage
    {
        return OutcomeImage::query()->where('gaming_session_id', $sessionId)->first();
    }

    public function save(OutcomeImage $outcomeImage): OutcomeImage
    {
        $outcomeImage->save();
        return $outcomeImage;
    }
}
