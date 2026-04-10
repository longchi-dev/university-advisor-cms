<?php

namespace App\Repositories;

use App\Contracts\Repositories\IThemeRepository;
use App\Models\Theme;

class ThemeRepository implements IThemeRepository
{
    public function findById(string $id): ?Theme
    {
        return Theme::query()->find($id);
    }
}
