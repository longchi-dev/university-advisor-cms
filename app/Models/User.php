<?php

namespace App\Models;

use App\Enums\UserRoleEnum;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property UserRoleEnum $role
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => UserRoleEnum::class,
        ];
    }

    public static function make(string $name, string $email, string $password): static
    {
        return new static([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);
    }

    public function isViewer(): bool
    {
        return $this->role == UserRoleEnum::VIEWER;
    }

    public function isSettings(): bool
    {
        return $this->role == UserRoleEnum::SETTING;
    }

    public function isAdmin(): bool
    {
        return $this->role == UserRoleEnum::ADMIN;
    }
}
