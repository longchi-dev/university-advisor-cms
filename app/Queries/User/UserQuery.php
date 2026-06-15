<?php

namespace App\Queries\User;

class UserQuery
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public int $page,
        public int $perPage,
        public ?string $userType = null,
        public ?array $emails = [],
        public ?string $fromDate = null,
        public ?string $toDate = null,
    ) {

    }
}
