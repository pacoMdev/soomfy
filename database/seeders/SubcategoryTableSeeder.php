<?php

namespace Database\Seeders;

use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcategoryTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('subcategories')->truncate();

        $subcategories = [
            // Subcategorías de Vehículos (category_id: 1)
            [
                'category_id' => 1,
                'name' => 'Coches'
            ],
            [
                'category_id' => 1,
                'name' => 'Motos'
            ],
            [
                'category_id' => 1,
                'name' => 'Furgonetas'
            ],

            // Subcategorías de Tecnología (category_id: 2)
            [
                'category_id' => 2,
                'name' => 'Móviles'
            ],
            [
                'category_id' => 2,
                'name' => 'Ordenadores'
            ],
            [
                'category_id' => 2,
                'name' => 'Consolas'
            ],

            // Subcategorías de Inmuebles (category_id: 3)
            [
                'category_id' => 3,
                'name' => 'Pisos'
            ],
            [
                'category_id' => 3,
                'name' => 'Casas'
            ],
            [
                'category_id' => 3,
                'name' => 'Garajes'
            ],
            [
                'category_id' => 3,
                'name' => 'Oficinas'
            ],
            [
                'category_id' => 3,
                'name' => 'Locales'
            ],

            // Subcategorías de Hogar (category_id: 4)
            [
                'category_id' => 4,
                'name' => 'Muebles'
            ],
            [
                'category_id' => 4,
                'name' => 'Herramientas'
            ],
            [
                'category_id' => 4,
                'name' => 'Jardinería'
            ],

            // Subcategorías de Servicios (category_id: 5)
            [
                'category_id' => 5,
                'name' => 'Limpieza'
            ],
            [
                'category_id' => 5,
                'name' => 'Mudanzas'
            ],
            [
                'category_id' => 5,
                'name' => 'Reformas'
            ],
            [
                'category_id' => 5,
                'name' => 'Reparaciones'
            ],
            [
                'category_id' => 5,
                'name' => 'Clases particulares'
            ],

            // Subcategorías de Empleo (category_id: 6)
            [
                'category_id' => 6,
                'name' => 'Informática'
            ],
            [
                'category_id' => 6,
                'name' => 'Comercial'
            ],
            [
                'category_id' => 6,
                'name' => 'Hostelería'
            ],
            [
                'category_id' => 6,
                'name' => 'Construcción'
            ],
            [
                'category_id' => 6,
                'name' => 'Educación'
            ],

            // Subcategorías de Deporte (category_id: 7)
            [
                'category_id' => 7,
                'name' => 'Fútbol'
            ],
            [
                'category_id' => 7,
                'name' => 'Ciclismo'
            ],
            [
                'category_id' => 7,
                'name' => 'Running'
            ],
            [
                'category_id' => 7,
                'name' => 'Fitness'
            ],
            [
                'category_id' => 7,
                'name' => 'Tenis'
            ]


        ];

        foreach ($subcategories as $subcategory) {
            Subcategory::create($subcategory);
        }
    }
}