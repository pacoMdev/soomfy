<?php

namespace App\Models;
use App\Models\Product;
use App\Models\Transactions;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\UserResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithMedia;

    protected $fillable = [
        'name',
        'email',
        'password',
        'longitude',
        'latitude',
        'surname1',
        'surname2',
        'google_id',
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
    ];

    public function userMessages()
    {
        return $this->belongsTo(User::class, 'message', 'product_id', 'user_id');
    }
    public function products()
    {
        return $this->belongsTo(Product::class, 'user_id');
    }
    // Relacion NM ( usuarios / opiniones /  productos )
    // Esta funcion saca las opiniones que el usuario ha dado
    public function opinions() {
        return $this->belongsToMany(UserOpinion::class, 'user_id', 'id');
    }

    public function purchase()
    {   
        return $this->hasMany(Transactions::class , 'userBuyer_id');
    }
    public function sales()
    {   
        return $this->hasMany(Transactions::class , 'userSeller_id');
    }

    // Relacion NM (usuarios / opiniones / products)
    // Esta funcion saca las opiniones que el usuario ha dado
    public function favoritos()
    {
        return $this->belongsToMany(Product::class, 'productos_favoritos')
            ->withTimestamps();
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserResetPasswordNotification($token));
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->useFallbackUrl('/images/placeholder.jpg')
            ->useFallbackPath(public_path('/images/placeholder.jpg'));
    }


}
