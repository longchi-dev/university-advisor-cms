<?php

namespace App\Queries\Player;

class PlayerQuery
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public int $page,
        public int $perPage,
        public ?string $fromDate = null,
        public ?string $toDate = null,
    ) {

    }
}
