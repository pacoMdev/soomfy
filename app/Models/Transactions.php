<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;
    
    protected $table = 'transactions';
    protected $fillable = ['producto_id', 'seller_id', 'buyer_id'];

    // REL Producto
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')
        ->with('media');
    }

    // REL User Vendedor
    public function seller()
    {
        return $this->belongsTo(User::class, 'userSeller_id');
    }

    // REL User Comprador
    public function buyer()
    {
        return $this->belongsTo(User::class, 'userBuyer_id');
    }

}
