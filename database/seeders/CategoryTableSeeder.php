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
            ['name' => 'Vehículos'],
            ['name' => 'Tecnología'],
            ['name' => 'Inmuebles'],
            ['name' => 'Hogar'],
            ['name' => 'Servicios'],
            ['name' => 'Empleo'],
            ['name' => 'Deporte'],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create([
                'name' => $categoryData['name']
            ]);


        }
    }


    
}