<?php

namespace App\Repositories;

use App\Contracts\Repositories\IUserRepository;
use App\Models\User;

class UserRepository implements IUserRepository
{
    public function findById(string $id): ?User
    {
        return User::query()->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }

    public function save(User $user): User
    {
        $user->save();
        return $user;
    }
    public function delete(User $user): void
    {
        $user->delete();
    }
}
