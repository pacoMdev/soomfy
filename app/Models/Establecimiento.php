<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\Transactions;

class Establecimiento extends Model
{
    use HasFactory;
    protected $table = 'establecimiento';
    protected $fillable = [
        'nombre',
        'direccion',
        'zip',
        'poblacion',
        'ciudad',
        'telefono',
        'email',
        'nombre_comercial',
    ];

    public function transactions(){
        return $this->hasMany(Transactions::class, 'establecimiento_id');
    }
}
