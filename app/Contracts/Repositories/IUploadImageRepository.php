<?php

namespace App\Contracts\Repositories;

use App\Models\UploadImage;

interface IUploadImageRepository
{
    public function findById(string $id): ?UploadImage;

    public function save(UploadImage $uploadImage): UploadImage;
}
