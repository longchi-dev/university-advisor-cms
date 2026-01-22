<?php

namespace App\Repositories;

use App\Contracts\Repositories\IKeywordRepository;
use App\Models\Keyword;
use Illuminate\Database\Eloquent\Collection;

class KeywordRepository implements IKeywordRepository
{
    public function getKeywords(): Collection
    {
        return Keyword::with('labels')->get();
    }
}
