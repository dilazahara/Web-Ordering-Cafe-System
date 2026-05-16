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
        'role',
        'username',
        'phone',
        'avatar',
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
            'password' => 'hashed',
        ];
    }

    /**
     * Generate formatted ID berdasarkan role dan ID asli.
     * admin → AD-001 | kasir → KS-001 | dapur → DP-001 | pelayan → PL-001
     */
    public function getFormattedIdAttribute(): string
    {
        $prefixes = [
            'admin'   => 'AD',
            'kasir'   => 'KS',
            'dapur'   => 'DP',
            'pelayan' => 'PL',
        ];

        $prefix = $prefixes[$this->role] ?? 'US';

        return $prefix . '-' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }
}