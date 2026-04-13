<?php

namespace App\Repositories;

use App\Contracts\Repositories\IThemeRepository;
use App\Models\Theme;
use Illuminate\Database\Eloquent\Collection;

class ThemeRepository implements IThemeRepository
{
    public function findById(int $id): ?Theme
    {
        return Theme::query()->find($id);
    }

    public function getAll(): Collection
    {
        return Theme::query()->orderBy('order')->get();
    }

    public function save(Theme $theme): Theme
    {
        $theme->save();
        return $theme;
    }
}
