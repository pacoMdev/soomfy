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
        $categories = [
            [
                'name' => 'Vehículos',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.'car_5615874.webp'
            ],
            [
                'name' => 'Coches',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.'coches.webp'
            ],
            [
                'name' => 'Motos',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.'motos.webp'
            ],
            [
                'name' => 'Camiones',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.'camiones.webp'
            ],
            [
                'name' => 'Tecnología',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.'tecnologia.webp'
            ],
            [
                'name' => 'Tablets',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.'tablets.webp'
            ],
            [
                'name' => 'Laptops',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.'laptops.webp'
            ],
            [
                'name' => 'Inmuebles',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.'inmuebles.webp'
            ],
            [
                'name' => 'Casas',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.'casas.webp'
            ],
            [
                'name' => 'Apartamentos',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.'apartamentos.webp'
            ],
            [
                'name' => 'Hogar',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.'muebles.webp'
            ],
            [
                'name' => 'Decoración',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.'decoracion.webp'
            ],
            [
                'name' => 'Electrodomésticos',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.'electrodomesticos.webp'
            ],
            [
                'name' => 'Servicios',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.'construccion.webp'
            ],
            [
                'name' => 'Construcción',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.'construccion.webp'
            ],
            [
                'name' => 'Empleo',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.'jobs.webp'
            ],
            [
                'name' => 'Deporte', 
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.'deporte.webp'
            ],
            [
                'name' => 'Fitness',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.'fitness.webp'
            ],
            [
                'name' => 'Ciclismo',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.'ciclismo.webp'
            ],
        ];

        foreach ($categories as $categoryData) {
            $categories = Category::create([
                'name' => $categoryData['name']
            ]);
            // Imagenes subidas por media (spatieMedia)
            if (isset($categoryData['images'])) {
                $categories->addMedia(storage_path($categoryData['images']))->preservingOriginal()->toMediaCollection('images');

            }
        }
    }


    
}