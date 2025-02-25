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
            ['name' => 'Vehículos', 'default_image' => 'categories/vehiculos.png'],
            ['name' => 'Tecnología', 'default_image' => 'categories/tecnologia.png'],
            ['name' => 'Inmuebles', 'default_image' => 'categories/inmuebles.png'],
            ['name' => 'Hogar', 'default_image' => 'categories/hogar.png'],
            ['name' => 'Servicios', 'default_image' => 'categories/servicios.png'],
            ['name' => 'Empleo', 'default_image' => 'categories/empleo.png'],
            ['name' => 'Deporte', 'default_image' => 'categories/deporte.png']
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create([
                'name' => $categoryData['name']
            ]);

            // Check if the specified default image exists in the public disk
            if (Storage::disk('public')->exists($categoryData['default_image'])) {
                // Add the image from the public disk to the media collection named 'category_image'
                $category->addMediaFromDisk($categoryData['default_image'], 'public')
                    ->toMediaCollection('category_image'); // Save the image
            }
        }
    }


    
}