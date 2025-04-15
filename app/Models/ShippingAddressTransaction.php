<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddressTransaction extends Model
{
    use HasFactory;
    protected $table = 'shipping_address_transactions';
    protected $fillable = ['shipping_address_id', 'transaction_id', 'status', 'traking_number'];
    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class, 'shipping_address_id');
    }
    public function transaction()
    {
        return $this->belongsTo(Transactions::class, 'transactions_id');
    }
}
