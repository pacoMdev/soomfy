<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = ['name'];


    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('original_image')
            ->singleFile()
            ->useDisk('public');
    }






}