<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // admin atau penyuluh
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
        ];
    }

    // Relasi: Satu User (Penyuluh) memiliki banyak Kegiatan
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    // Helper function untuk cek role
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPenyuluh(): bool
    {
        return $this->role === 'penyuluh';
    }
}
