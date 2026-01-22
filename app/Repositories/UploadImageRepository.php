<?php

namespace App\Repositories;

use App\Contracts\Repositories\IUploadImageRepository;
use App\Models\UploadImage;

class UploadImageRepository implements IUploadImageRepository
{
    public function findById(string $id): ?UploadImage
    {
        return UploadImage::query()->find($id);
    }

    public function save(UploadImage $uploadImage): UploadImage
    {
        $uploadImage->save();
        return $uploadImage;
    }
}
