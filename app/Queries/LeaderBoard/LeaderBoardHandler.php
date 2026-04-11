<?php

namespace App\Queries\LeaderBoard;

use App\Models\LeaderBoard;
use Illuminate\Pagination\LengthAwarePaginator;

class LeaderBoardHandler
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function execute(LeaderBoardQuery $query): LengthAwarePaginator
    {
        $leaderBoardQuery = LeaderBoard::query()
            ->when($query->weekNumber, function ($q) use ($query) {
                $q->where('week_number', $query->weekNumber);
            })
            ->orderBy('rank', 'asc');

        $paginator = $leaderBoardQuery->paginate(
            $query->perPage, [
                'id',
                'rank',
                'name',
                'phone',
                'week_number',
                'created_at'
            ], 'page', $query->page
        );

        $paginator->getCollection()->transform(function (LeaderBoard $leaderBoard) {
            return [
                'rank' => $leaderBoard->rank,
                'player_name' => $leaderBoard->name,
                'phone' => $leaderBoard->getMaskPhone(),
                'week_number' => $leaderBoard->week_number,
//                'created_at' => $leaderBoard->created_at->format('d-m-Y H:i:s'),
            ];
        });

        return $paginator;
    }
}
