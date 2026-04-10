<?php

namespace App\Contracts\Repositories;

use App\Models\Theme;

interface IThemeRepository
{
    public function findById(string $id): ?Theme;
}
