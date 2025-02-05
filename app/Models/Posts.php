<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'price', 'estado', 'location', 'toSend', 'isDeleted', 'isBoost', 'dimensionX', 'dimensionY', 'marca', 'color', 'category_id'];


    /**
     * Get the category that owns the post.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_post');
    }
}
