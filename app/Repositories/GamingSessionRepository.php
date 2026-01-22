<?php

namespace App\Repositories;

use App\Contracts\Repositories\IGamingSessionRepository;
use App\Models\GamingSession;

class GamingSessionRepository implements IGamingSessionRepository
{
    public function findById(string $id): ?GamingSession
    {
        return GamingSession::query()->find($id);
    }

    public function save(GamingSession $gamingSession): GamingSession
    {
        $gamingSession->save();
        return $gamingSession;
    }
}
