<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HashHelper
{
    public static function internalHashPhone($phone): string
    {
        return sha1($phone);
    }
}
