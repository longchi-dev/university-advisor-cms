<?php

namespace App\Queries\Player;

use App\Models\Player;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
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
            ->when($query->playerUrl, function ($q) use ($query) {
                $q->where('full_url', $query->playerUrl);
            })
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

    public function getPlayerUrls(PlayerQuery $query): Collection
    {
        return Player::query()
            ->whereNotNull('full_url')
            ->distinct()
            ->orderBy('full_url')
            ->pluck('full_url');
    }

}
