<?php

namespace App\Models;

class Role extends \Spatie\Permission\Models\Role
{
    const NAME_AGENCY = 'agency';
    const NAME_ADMIN = 'admin';

    public static function make($name): static
    {
        return new static(['name' => $name, 'guard_name' => 'web']);
    }
}
