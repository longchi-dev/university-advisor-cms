<?php

namespace App\Queries\DataAdvisor;

class DataAdvisorQuery
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public int $page,
        public int $perPage,
        public ?string $id = null,
        public ?int $year = null,
        public ?string $school = null,
        public ?string $major = null,
    )
    {
        //
    }
}
