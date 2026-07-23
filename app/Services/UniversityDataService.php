<?php

namespace App\Services;

use App\DTOs\UniversityDataDTO;
use App\Models\Major;
use App\Models\School;
use App\Models\Score;
use Illuminate\Support\Facades\DB;

class UniversityDataService
{
    public function importData(array $items): void
    {
        DB::transaction(function () use ($items) {

            $schoolCache = [];
            $majorCache = [];

            foreach ($items as $item) {
                $dto = UniversityDataDTO::fromArray($item);

                $schoolKey = mb_strtolower(trim($dto->school));
                $majorKey  = mb_strtolower(trim($dto->major));

                if (!isset($schoolCache[$schoolKey])) {
                    $schoolCache[$schoolKey] = School::query()->firstOrCreate(
                        ['name' => $dto->school],
                        [
                            'address' => $dto->address,
                            'phone' => $dto->phone,
                            'website' => $dto->website,
                        ]
                    );
                }
                $school = $schoolCache[$schoolKey];

                if (!isset($majorCache[$majorKey])) {
                    $majorCache[$majorKey] = Major::query()->firstOrCreate([
                        'name' => $dto->major
                    ]);
                }
                $major = $majorCache[$majorKey];

                Score::query()->updateOrCreate(
                    [
                        'school_id' => $school->getKey(),
                        'major_id' => $major->getKey(),
                        'year' => $dto->year,
                    ],
                    [
                        'score' => $dto->score,
                        'block' => $dto->block,
                        'level' => $dto->level,
                        'note' => $dto->note,
                        'source_url' => $dto->sourceUrl,
                    ]
                );
            }
        });
    }
}
