<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'telephone',
        'role',
        'actif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'actif' => 'boolean',
        ];
    }

    const ROLE_ADMIN = 'admin';
    const ROLE_GESTIONNAIRE = 'gestionnaire';
    const ROLE_UTILISATEUR = 'utilisateur';

    public static function getRoles(): array
    {
        return [
            self::ROLE_ADMIN => 'Administrateur',
            self::ROLE_GESTIONNAIRE => 'Gestionnaire',
            self::ROLE_UTILISATEUR => 'Utilisateur',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->actif && in_array($this->role, [self::ROLE_ADMIN, self::ROLE_GESTIONNAIRE]);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isGestionnaire(): bool
    {
        return $this->role === self::ROLE_GESTIONNAIRE;
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
