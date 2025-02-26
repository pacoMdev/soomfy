<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CategoryTableSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Vehículos',
                'default_image' => ['vehiculos.jpg']
            ],
            [
                'name' => 'Tecnología',
                'default_image' => ['tecnologia.jpg', 'tecnologia2.jpg']
            ],
            [
                'name' => 'Hogar',
                'default_image' => ['hogar.jpg', 'hogar2.jpg']
            ],
            [
                'name' => 'Jardín',
                'default_image' => ['jardin.jpg']
            ],
            [
                'name' => 'Electrónica',
                'default_image' => ['electronica.jpg']
            ],
            [
                'name' => 'Empleo',
                'default_image' => ['empleo.jpg']
            ],
            [
                'name' => 'Deportes',
                'default_image' => ['deportes.jpg']
            ]
        ];


        foreach ($categories as $categoryData) {
            $category = new Category();
            $category->name = $categoryData['name'];
            $category->save();

            foreach ($categoryData['default_image'] as $default_image) {
                $default_imagePath = storage_path('app/public/seed_images/categories/' . $default_image);
                if (file_exists($default_imagePath)) {
                    $category->addMedia($default_imagePath)
                        ->preservingOriginal()
                        ->toMediaCollection('default_images');
                }
            }
        }
    }




    }