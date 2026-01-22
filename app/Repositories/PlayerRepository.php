<?php

namespace App\Repositories;

use App\Contracts\Repositories\IGamingSessionRepository;
use App\Contracts\Repositories\IPlayerRepository;
use App\Models\Player;

class PlayerRepository implements IPlayerRepository
{
    public function findById(string $id): ?Player
    {
        return Player::query()->find($id);
    }

    public function save(Player $player): Player
    {
        $player->save();
        return $player;
    }
}
