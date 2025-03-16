<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $table = 'shipping_address';

    protected $fillable = [
        'transaction_id',
        'user_id',
        'address',
        'city',
        'postal_code',
        'country',
        'status',
        'tracking_number',
    ];

    public function transaction(){
        return $this -> belongsTo(Transactions::class);
    }

    public function user(){
        return $this -> belongsTo(User::class);
    }
}
