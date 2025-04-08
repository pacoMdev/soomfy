<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleAddress extends Model
{
    use HasFactory;
    protected $table = 'role_address';
    protected $fillable = [
        'name',
    ];
    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class, 'role_address');
    }
}
