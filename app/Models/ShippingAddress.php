<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $table = 'shipping_address';

    protected $fillable = [
        'address',
        'zip',
        'country',
        'city',
        'role_address',
        'notes',
        'contact_name',
        'contact_phone',
        'contact_email',
    ];

    public function transaction(){
        return $this -> belongsToMany(Transactions::class)
        ->withPivot('status', 'traking_number')
        ->withTimestamps();
    }
}
