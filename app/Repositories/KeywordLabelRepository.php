<?php

namespace App\Repositories;

use App\Contracts\Repositories\IKeywordLabelRepository;
use App\Models\KeywordLabel;
use Illuminate\Database\Eloquent\Collection;

class KeywordLabelRepository implements IKeywordLabelRepository
{
    public function findById(int $id): ?KeywordLabel
    {
        return KeywordLabel::query()->find($id);
    }

    public function getByIds(array $ids): Collection
    {
        return KeywordLabel::query()->whereIn('id', $ids)->get();
    }

    public function getByKeywordId(int $keywordId): Collection
    {
        return KeywordLabel::query()->where('keyword_id', $keywordId)->get();
    }
}
