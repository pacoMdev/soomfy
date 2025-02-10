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
            [
                'nameImg' => 'cat_chellPhone.webp',
                'name' => 'Moviles',
            ],
            [
                'nameImg' => 'cat_technology.webp',
                'name' => 'Tecnologia',
            ],
            [
                'nameImg' => 'cat_car.webp',
                'name' => 'Coches',
            ],
            [
                'nameImg' => 'cat_moto.webp',
                'name' => 'Motos',
            ],
            [
                'nameImg' => 'cat_inmueble.webp',
                'name' => 'Inmueble',
            ],
            [
                'nameImg' => 'cat_tools.webp',
                'name' => 'Herramientas',
            ],
            [
                'nameImg' => 'cat_moda.webp',
                'name' => 'Moda',
            ],
            [
                'nameImg' => 'cat_services.webp',
                'name' => 'Servicios',
            ],
            [
                'nameImg' => 'cat_muebles.webp',
                'name' => 'Muebles',
            ],
            [
                'nameImg' => 'cat_empleto.webp',
                'name' => 'Empleo',
            ],
            [
                'nameImg' => 'cat_jardineria.webp',
                'name' => 'Jardineria',
            ],
            [
                'nameImg' => 'cat_deporte.webp',
                'name' => 'Deporte',
            ],
            [
                'nameImg' => 'cat_construcction.webp',
                'name' => 'Contruccion',
            ],
            [
                'nameImg' => 'cat_others.webp',
                'name' => 'Otros...',
            ],
        ];

        foreach ($categories as $cat) {
            $category = new Category();
            $category -> name = $cat['name'];
            $category -> nameImg = $cat['nameImg'];
            $category->save();
        }
    }
}
