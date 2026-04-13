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
        public ?string $themeId = null,
        public ?string $hasOutcome = null,
        public ?string $isSharedFb = null,
        public ?string $isSharedIg = null,
        public ?string $isSaved = null,
        public ?string $fromDate = null,
        public ?string $toDate = null,
    ) {

    }
}
