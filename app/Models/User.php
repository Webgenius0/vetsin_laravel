<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

      /**
     * Get the identifier that will be stored in the JWT subject claim.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Return the primary key of the user (id)
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'role',
        'provider',
        'provider_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'agree_to_terms' => 'boolean',
            'is_premium' => 'boolean',
            'id' => 'integer',
            'date_of_birth' => 'date',
            'preferred_age_min' => 'integer',
            'preferred_age_max' => 'integer',
            'budget_min' => 'decimal:2',
            'budget_max' => 'decimal:2',
            'tags' => 'array',
        ];
    }

    /**
     * Get user's age based on date of birth
     */
    public function getAgeAttribute()
    {
        if ($this->date_of_birth) {
            return Carbon::parse($this->date_of_birth)->age;
        }
        return null;
    }

    /**
     * Check if user profile is complete
     */
    public function getIsProfileCompleteAttribute()
    {
        return !empty($this->date_of_birth) &&
               !empty($this->location) &&
               !empty($this->relationship_goal) &&
               !empty($this->preferred_age_min) &&
               !empty($this->preferred_age_max);
    }

    /**
     * Check if real estate preferences are complete
     */
    public function getIsRealEstateCompleteAttribute()
    {
        return !empty($this->preferred_property_type) &&
               !empty($this->identity) &&
               !empty($this->budget_min) &&
               !empty($this->budget_max) &&
               !empty($this->preferred_location);
    }

    /**
     * Get favorites created by this user
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'user_id');
    }

    /**
     * Get users who favorited this user
     */
    public function favoritedBy()
    {
        return $this->hasMany(Favorite::class, 'favorite_user_id');
    }

    /**
     * Check if this user has favorited another user
     */
    public function hasFavorited($userId)
    {
        return $this->favorites()->where('favorite_user_id', $userId)->exists();
    }

    /**
     * Check if this user is favorited by another user
     */
    public function isFavoritedBy($userId)
    {
        return $this->favoritedBy()->where('user_id', $userId)->exists();
    }
}
