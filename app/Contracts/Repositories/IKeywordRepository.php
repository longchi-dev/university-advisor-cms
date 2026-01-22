<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface IKeywordRepository
{
    public function getKeywords(): Collection;
}
