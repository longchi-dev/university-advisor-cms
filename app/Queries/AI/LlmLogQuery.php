<?php

namespace App\Queries\AI;

class LlmLogQuery
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
