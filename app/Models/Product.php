<?php

namespace App\Models;

use Couchbase\SearchResult;
use App\Models\Transactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


//use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'products';
    protected $fillable = ['title', 'content', 'price','category_id', 'user_id','estado_id', 'toSend'];
    
    // Relacion NM ( usuarios / products )
    public function users()
    {   
        return $this->belongsTo(User::class, 'product_id', 'user_id');
    }

    // Esta funcion saca las opiniones que tiene cada producto productos
    public function opinions() {
        // return $this->belongsToMany(UserOpinion::class, 'product_id', 'id');
        return $this->belongsToMany(User::class, 'usersOpinion')
        ->withPivot('title', 'destription', 'calification', 'token')
        ->withTimestamps();
    }

    // Relacion 1N tabla estados
    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    // Relacion: Obtener el usuario del producto
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->with('media');
    }

    public function transactions()
    {
        return $this->hasMany(Transactions::class, 'product_id');
    }

    // Relacion N:M entre productos
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

  

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useFallbackUrl('/images/placeholder.jpg')
            ->useFallbackPath(public_path('/images/placeholder.jpg'));
    }

    public function registerMediaConversions(Media $media = null): void
    {
        if (env('RESIZE_IMAGE') === true) {

            $this->addMediaConversion('resized-image')
                ->width(env('IMAGE_WIDTH', 300))
                ->height(env('IMAGE_HEIGHT', 300));
        }
    }
}
