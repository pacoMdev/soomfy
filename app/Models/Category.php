<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get the productos for the category.
     */
    public function posts()
    {
        // $table = 'categories';
        return $this->belongsToMany(Product::class,'category_product');
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercises::class,'category_exercise');
    }

}
