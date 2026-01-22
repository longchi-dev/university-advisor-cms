<?php

namespace App\Contracts\Repositories;

use App\Models\GamingSession;

interface IGamingSessionRepository
{
    public function findById(string $id): ?GamingSession;

    public function save(GamingSession $gamingSession): GamingSession;
}
