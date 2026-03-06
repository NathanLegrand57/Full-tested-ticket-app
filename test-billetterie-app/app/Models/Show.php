<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Show extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'show_date',
        'image',
        'duration',
        'price',
        'places_disponibles',
    ];

    protected $casts = [
        'show_date' => 'datetime',
        'price' => 'float',
        'duration' => 'integer',
        'places_disponibles' => 'integer',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get all favorites for this show.
     */
    public function favorites()
    {
        return $this->hasMany(UserFavorite::class);
    }

    /**
     * Get users who favorited this show.
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'user_favorites')
                    ->withTimestamps();
    }

    /**
     * Check if the show is favorited by the authenticated user.
     */
    public function isFavorited(?int $userId = null): bool
    {
        if (!$userId && auth()->check()) {
            $userId = auth()->id();
        }

        if (!$userId) {
            return false;
        }

        return $this->favorites()
                    ->where('user_id', $userId)
                    ->exists();
    }

    /**
     * Get the count of users who favorited this show.
     * Uses favorites_count from withCount() if available, otherwise counts manually.
     */
    public function getFavoritesCountAttribute(): int
    {
        if (isset($this->attributes['favorites_count'])) {
            return (int) $this->attributes['favorites_count'];
        }
        
        return $this->favorites()->count();
    }
}
