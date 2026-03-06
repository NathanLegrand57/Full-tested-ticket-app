<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRolesAndAbilities;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lastname',
        'firstname',
        'email',
        'phone_number',
        'password',
    ];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the user's reservations.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get the user's cart items.
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get the user's favorites.
     */
    public function favorites()
    {
        return $this->hasMany(UserFavorite::class);
    }

    /**
     * Get the shows favorited by the user.
     */
    public function favoriteShows()
    {
        return $this->belongsToMany(Show::class, 'user_favorites')
            ->withTimestamps()
            ->orderBy('user_favorites.created_at', 'desc');
    }
}
