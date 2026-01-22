<?php

namespace App\Contracts\Repositories;

use App\Models\Player;

interface IPlayerRepository
{
    public function findById(string $id): ?Player;

    public function save(Player $player): Player;
}
