<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthHelper
{
    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|User|null
     */
    public static function getUser(): \Illuminate\Contracts\Auth\Authenticatable|User|null
    {
        return Auth::user();
    }
}
