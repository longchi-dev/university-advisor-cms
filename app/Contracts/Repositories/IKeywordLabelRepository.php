<?php

namespace App\Contracts\Repositories;

use App\Models\KeywordLabel;
use Illuminate\Database\Eloquent\Collection;

interface IKeywordLabelRepository
{
    public function findById(int $id): ?KeywordLabel;

    public function getByIds(array $ids): Collection;

    public function getByKeywordId(int $keywordId): Collection;
}
