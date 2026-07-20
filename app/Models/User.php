<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'bio',
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

    /** A moderator can resolve flags. */
    public function isModerator(): bool
    {
        return $this->role === 'moderator';
    }

    // ── Relationships ──────────────────────────────

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function flags()
    {
        return $this->hasMany(Flag::class);
    }

    public function resolvedFlags()
    {
        return $this->hasMany(Flag::class, 'resolved_by');
    }
}
