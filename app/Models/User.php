<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Roles;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factory_recovery_codes',
        'remember_token',
    ];

    protected $appends = [
        'is_admin',
        'is_owner',
        'is_cashier',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected function isAdmin(): Attribute{
        return new Attribute(
            get: fn () => $this->roles->filter(function ($role) {
                return $role->description === Roles::ADMIN->value;
            })->count() === 1
        );
    }

    protected function isOwner(): Attribute{
        return new Attribute(
            get: fn () => $this->roles->filter(function ($role) {
                return $role->description === Roles::OWNER->value;
            })->count() === 1
        );
    }

    protected function isCashier(): Attribute{
        return new Attribute(
            get: fn () => $this->roles->filter(function ($role) {
                return $role->description === Roles::CASHIER->value;
            })->count() === 1
        );
    }

    public function roles(): BelongsToMany{
        return $this->belongsToMany(Role::class);
    }


}
