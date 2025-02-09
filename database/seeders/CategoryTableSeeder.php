<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Moviles',
            'Tecnologia',
            'Coches',
            'Motos',
            'Inmuebles',
            'Herramientas',
            'Moda',
            'Servicios',
            'Muebles',
            'Empleo',
            'Jardineria',
            'Deporte',
            'Construccion',
            'Otros...',
        ];


        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
