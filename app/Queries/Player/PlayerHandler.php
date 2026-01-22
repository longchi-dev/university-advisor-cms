<?php

namespace App\Queries\Player;

use App\Models\Player;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PlayerHandler
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {

    }

    public function execute(PlayerQuery $query): LengthAwarePaginator
    {
        $playerQuery = Player::query()
            ->whereBetween(DB::raw('DATE(created_at)'), [$query->fromDate, $query->toDate])
            ->orderByDesc('created_at');

        $paginator = $playerQuery->paginate($query->perPage, ['*'], 'page', $query->page);

        $paginator->getCollection()->transform(function (Player $player) {
            return [
                'full_url' => $player->full_url,
                'player_name' => $player->name,
                'created_at' => $player->created_at
            ];
        });

        return $paginator;
    }
}
