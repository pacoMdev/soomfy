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
                'name' => 'Tecnología',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.'tecnologia.webp'
            ],
            [
                'name' => 'Inmuebles',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.'inmuebles.webp'
            ],
            [
                'name' => 'Hogar',
                'images' => DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.'muebles.webp'
            ],
            [
                'name' => 'Servicios',
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