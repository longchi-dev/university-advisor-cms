<?php

namespace App\Contracts\Repositories;

use App\Models\User;

interface IUserRepository
{
    public function findById(string $id): ?User;
    public function findByEmail(string $email): ?User;
    public function save(User $user): User;
    public function delete(User $user): void;
}
