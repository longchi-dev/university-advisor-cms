<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    public static function getUriImage(string $imageName): string
    {
        return Storage::disk('s3')->url($imageName);
//        return config('filesystems.disks.s3.bucket') . '/'. $imageName;
    }

    public static function getImageUrl(?string $imageName): string
    {
        if (!$imageName) {
            return '';
        }
        return Storage::disk('s3')->url($imageName);
        $baseUrl = rtrim(config('filesystems.disks.s3.cdn_url'), '/');

        return "{$baseUrl}/{$imageName}";
    }
}
