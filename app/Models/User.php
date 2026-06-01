<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'expires_at',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'expires_at'        => 'datetime',
            'is_admin'          => 'boolean',
            'password'          => 'hashed',
        ];
    }

    /** Admin হলে সবসময় active, না হলে expires_at চেক করো */
    public function isActive(): bool
    {
        if ($this->is_admin) return true;
        if (is_null($this->expires_at)) return false;
        return $this->expires_at->isFuture();
    }

    /** কতদিন বাকি */
    public function daysRemaining(): int
    {
        if ($this->is_admin) return 9999;
        if (is_null($this->expires_at)) return 0;
        return max(0, (int) now()->diffInDays($this->expires_at, false));
    }

    public function pumpOwner()
    {
        return $this->hasOne(PumpOwner::class);
    }

    public function farmers()
    {
        return $this->hasMany(Farmer::class);
    }
}
