<?php

namespace App\Queries\DataAdvisor;

use App\Models\Score;
use App\Models\UniversityEmbedding;
use Illuminate\Pagination\LengthAwarePaginator;

class DataAdvisorHandler
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function execute(DataAdvisorQuery $query): LengthAwarePaginator
    {
        return Score::query()
            ->with(['school', 'major'])

            ->when($query->year, function ($q) use ($query) {
                $q->where('year', $query->year);
            })

            ->when($query->school, function ($q) use ($query) {
                $q->whereHas('school', function ($school) use ($query) {
                    $school->where(
                        'name',
                        'ilike',
                        "%{$query->school}%"
                    );
                });
            })

            ->when($query->major, function ($q) use ($query) {
                $q->whereHas('major', function ($major) use ($query) {
                    $major->where(
                        'name',
                        'ilike',
                        "%{$query->major}%"
                    );
                });
            })
            ->orderByDesc('year')
            ->paginate(
                $query->perPage,
                ['*'],
                'page',
                $query->page
            );
    }
}
