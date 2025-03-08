<?php

namespace Database\Seeders;

use App\Models\Estado;
use Illuminate\Database\Seeder;

class EstadosTableSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            ['nombre' => 'Nuevo'],
            ['nombre' => 'Como nuevo'],
            ['nombre' => 'Buen estado'],
            ['nombre' => 'Aceptable'],
            ['nombre' => 'Para reparar'],
        ];

        foreach ($estados as $estado) {
            Estado::create($estado);
        }
    }
}