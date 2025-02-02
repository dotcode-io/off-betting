<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserStatus;
use App\Enums\UserType;
use Database\Factories\UserFactory;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Override;

final class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    /** @use HasFactory<UserFactory> */
    use Authenticatable, Authorizable, HasFactory, HasUuids;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    public $casts = [
        'status' => UserStatus::class,
        'user_type' => UserType::class,
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    #[Override]
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function getEmailForVerification()
    {
        return $this->username;
    }

    public function isAdmin(): bool
    {
        return $this->user_type === UserType::ADMIN;
    }

    public function isTeller(): bool
    {
        return $this->user_type === UserType::TELLER;
    }

    public function isController(): bool
    {
        return $this->user_type === UserType::CONTROLLER;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
