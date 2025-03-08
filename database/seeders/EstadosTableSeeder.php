<?php

namespace Database\Seeders;

use App\Models\Estado;
use Illuminate\Database\Seeder;

class EstadosTableSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            ['name' => 'Nuevo'],
            ['name' => 'Como nuevo'],
            ['name' => 'Buen estado'],
            ['name' => 'Aceptable'],
            ['name' => 'Para reparar'],
        ];

        foreach ($estados as $estado) {
            Estado::create($estado);
        }
    }
}