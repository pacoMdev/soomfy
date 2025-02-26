<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get the posts for the category.
     */
    public function posts()
    {
        // $table = 'categories';
        return $this->belongsToMany(Post::class,'category_post');
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercises::class,'category_exercise');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('category_image')
            ->useDisk('public');
    }

    // Sobrescribir el método que determina la ruta de almacenamiento
    public function getMediaFolderPath(): string
    {
        return 'categories/' . $this->id;
    }



}
