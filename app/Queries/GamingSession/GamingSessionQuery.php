<?php

namespace App\Queries\GamingSession;

class GamingSessionQuery
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
