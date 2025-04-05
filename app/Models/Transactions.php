<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Transactions extends Model
{
    use HasFactory;
    
    protected $table = 'transactions';
    protected $fillable = ['producto_id', 'userSeller_id', 'userBuyer_id'];

    // 1:N Transaction && Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')
        ->with('media');
    }

    // 1:N User && Vendedor
    public function seller()
    {
        return $this->belongsTo(User::class, 'userSeller_id');
    }

    // 1:N User && Comprador
    public function buyer()
    {
        return $this->belongsTo(User::class, 'userBuyer_id');
    }

    // 1:N Transaction && shippingAddress
    public function shippingAddress() {
        return $this->hasOne(ShippingAddress::class, 'transaction_id');
    }

    public function establecimientos(){
        return $this->belongsToMany(Establecimiento::class, 'establecimiento_transactions');
    }

}
