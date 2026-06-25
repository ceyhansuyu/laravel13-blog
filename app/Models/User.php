<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany; // HasMany ilişkisi için ekledik

// Yeni alanlarımızı Laravel'in modern Fillable attribute yapısına dahil ettik
#[Fillable([
    'name', 
    'email', 
    'show_email', 
    'password', 
    'bio', 
    'avatar', 
    'github_url', 
    'linkedin_url', 
    'twitter_url',
    'role'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
     * Kullanıcının yazdığı tüm blog yazılarına erişim sağlar.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}