<?php

namespace App\Contracts\Repositories;

use App\Models\Theme;
use Illuminate\Database\Eloquent\Collection;

interface IThemeRepository
{
    public function findById(int $id): ?Theme;
    public function getAll(): Collection;
    public function save(Theme $theme): Theme;
}
