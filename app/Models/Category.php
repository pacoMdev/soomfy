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
    protected $appends = ['category_image'];

    public function getCategoryImageAttribute()
    {
        return $this->getFirstMediaUrl('category_image');
    }

    protected $fillable = ['name'];

    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('category_image')
            ->useDisk('public');

    }


}