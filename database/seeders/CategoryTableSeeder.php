<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
        public function run()
    {
        $categories_svg = [
            [
                'name' => 'Vehiculos',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories_svg'.DIRECTORY_SEPARATOR.'vehiculos.svg'
            ],
            [
                'name' => 'Coches',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories_svg'.DIRECTORY_SEPARATOR.'car.svg'
            ],
            [
                'name' => 'Motos',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories_svg'.DIRECTORY_SEPARATOR.'moto.svg'
            ],
            [
                'name' => 'Camiones',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories_svg'.DIRECTORY_SEPARATOR.'camion.svg'
            ],
            [
                'name' => 'Tecnologia',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories_svg'.DIRECTORY_SEPARATOR.'tecnologia.svg'
            ],
            [
                'name' => 'Tablets',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories_svg'.DIRECTORY_SEPARATOR.'tablet.svg'
            ],
            [
                'name' => 'Laptops',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories_svg'.DIRECTORY_SEPARATOR.'laptop.svg'
            ],
            [
                'name' => 'Inmuebles',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories_svg'.DIRECTORY_SEPARATOR.'inmuebles.svg'
            ],
            [
                'name' => 'Casas',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories_svg'.DIRECTORY_SEPARATOR.'casa.svg'
            ],
            [
                'name' => 'Apartamentos',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories_svg'.DIRECTORY_SEPARATOR.'apartamento.svg'
            ],
            [
                'name' => 'Hogar',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories_svg'.DIRECTORY_SEPARATOR.'muebles.svg'
            ],
            [
                'name' => 'Decoracion',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories_svg'.DIRECTORY_SEPARATOR.'decoracion.svg'
            ],
            [
                'name' => 'Electrodomesticos',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories_svg'.DIRECTORY_SEPARATOR.'electrodomesticos.svg'
            ],
            [
                'name' => 'Servicios',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories_svg'.DIRECTORY_SEPARATOR.'servicios.svg'
            ],
            [
                'name' => 'Construccion',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories_svg'.DIRECTORY_SEPARATOR.'construccion.svg'
            ],
            [
                'name' => 'Empleo',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories_svg'.DIRECTORY_SEPARATOR.'jobs.svg'
            ],
            [
                'name' => 'Deporte', 
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories_svg'.DIRECTORY_SEPARATOR.'deporte.svg'
            ],
            [
                'name' => 'Fitness',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories_svg'.DIRECTORY_SEPARATOR.'fitness.svg'
            ],
            [
                'name' => 'Ciclismo',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories_svg'.DIRECTORY_SEPARATOR.'ciclismo.svg'
            ],
        ];

        foreach ($categories_svg as $categoryData) {
            $categories_svg = Category::create([
                'name' => $categoryData['name']
            ]);
            // Imagenes subidas por media (spatieMedia)
            if (isset($categoryData['images'])) {
                $categories_svg->addMedia(storage_path($categoryData['images']))->preservingOriginal()->toMediaCollection('images');

            }
        }
    }


    
}