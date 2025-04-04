<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CategoryProduct extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;



    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('original_image')
            ->singleFile()
            ->useDisk('public');
    }






}