<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Service;
use App\Models\Category;
use App\Models\UserService;
use App\Models\ClientRating;
use App\Models\Notification;
use Laravel\Sanctum\HasApiTokens;
use App\Models\ProfessionalRating;
use App\Models\ProfessionalProjectImage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'mobile_number',
        'phone_number',
        'image',
        'cr_copy',
        'id_copy',
        'location',
        'latitude',
        'longitude',
        'role',
        'type',
        'experience',
        'total_project',
        'project_done_by_app',
        'email',
        'password'
    ];

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
     * The services that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'user_services', 'user_id', 'service_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'user_categories', 'user_id', 'category_id');
    }

    /**
     * Get all of the clientPosts for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clientPosts(): HasMany
    {
        return $this->hasMany(Post::class, 'client_id');
    }

    public function clientRating(): HasMany
    {
        return $this->hasMany(ClientRating::class, 'client_id');
    }

    public function professionalPosts(): HasMany
    {
        return $this->hasMany(Post::class, 'professional_id');
    }

    public function professionalProjectImage(): HasMany
    {
        return $this->hasMany(ProfessionalProjectImage::class, 'professional_id');
    }

    public function professionalRating(): HasMany
    {
        return $this->hasMany(ProfessionalRating::class, 'professional_id');
    }

    public function notification(): HasMany
    {
        return $this->hasMany(Notification::class, 'receiver_id');
    }

    public function getOverallProfessionalRatingAttribute()
    {
        $ratings = $this->professionalRating()->pluck('rating')->toArray();

        if (count($ratings) === 0) {
            return 0; // Or any default value you prefer
        }

        $averageRating = array_sum($ratings) / count($ratings);

        return round($averageRating, 1);
    }

    public function getOverallClientRatingAttribute()
    {
        $ratings = $this->clientRating()->pluck('rating')->toArray();

        if (count($ratings) === 0) {
            return 0; // Or any default value you prefer
        }

        $averageRating = array_sum($ratings) / count($ratings);

        return round($averageRating, 1);
    }
}
