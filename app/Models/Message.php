<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $table = 'message';

    public function userRemitent()
    {
        return $this->belongsTo(User::class, 'userRemitent_id');
    }

    public function userDestination()
    {
        return $this->belongsTo(User::class, 'userDestination_id');
    }
}
