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
            ->with('quizAnswers')
            ->whereBetween(DB::raw('DATE(created_at)'), [$query->fromDate, $query->toDate])
            ->orderByDesc('created_at');

        if ($query->playerType !== null && $query->playerType !== '') {
            if ($query->playerType === '1') {
                $playerQuery->whereNull('last_return_at');
            }

            if ($query->playerType === '0') {
                $playerQuery->whereNotNull('last_return_at');
            }
        }

        if (is_array($query->phones) && count($query->phones) > 0) {
            $playerQuery->whereIn('phone', $query->phones);
        }

        $paginator = $playerQuery->paginate($query->perPage, ['*'], 'page', $query->page);

        $paginator->getCollection()->transform(function (Player $player) {
            $answers = $player->quizAnswers
                ->pluck('label_snapshot')
                ->filter()
                ->unique()
                ->implode(', ');

            return [
                'last_name' => $player->last_name,
                'first_name' => $player->first_name,
                'email' => $player->email,
                'phone' => $player->phone,
                'terms_of_use' => $player->confirmed_terms_at ? "Chấp nhận" : "Từ chối",
                'user_type' => $player->last_return_at ? 'Người chơi cũ' : 'Người chơi mới',
                'answers' => $answers,
                'confirmed_terms_at' => $player->confirmed_terms_at?->format('d-m-Y H:i:s') ?? null,
                'created_at' => $player->created_at,
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
