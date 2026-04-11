<?php

namespace App\Queries\LeaderBoard;

class LeaderBoardQuery
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public int $page,
        public int $perPage,
        public ?int $weekNumber,
        public ?string $fromDate = null,
        public ?string $toDate = null,
    ) {

    }
}
