<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOpinion extends Model
{
    use HasFactory;
    protected $table="usersOpinion";

    protected $fillable = ['product_id', 'user_id', 'rating', 'comment'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->with('media');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->with('media');
    }
}
