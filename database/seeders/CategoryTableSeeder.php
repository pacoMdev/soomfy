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
                'name' => 'Tecnología',
                'image' => ['tecnologia.jpg', 'tecnologia2.jpg']
            ],
            [
                'name' => 'Deportes',
                'image' => ['deportes.jpg']
            ],
            [
                'name' => 'Hogar',
                'image' => ['hogar.jpg', 'hogar2.jpg']
            ],
            [
                'name' => 'Jardín',
                'image' => ['jardin.jpg']
            ],
            [
                'name' => 'Electrónica',
                'image' => ['electronica.jpg']
            ]
        ];

        foreach ($categories as $categoryData) {
            $category = new Category();
            $category->name = $categoryData['name'];
            $category->save();

            foreach ($categoryData['image'] as $image) {
                $imagePath = storage_path('app/public/seed_category/' . $image);
                if (file_exists($imagePath)) {
                    $category->addMedia($imagePath)
                        ->preservingOriginal()
                        ->toMediaCollection('images');
                }
            }
        }
    }




    }