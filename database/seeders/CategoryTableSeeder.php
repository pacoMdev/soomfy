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
                'subcategories' => [
                    ['name' => 'Smartphones', 'nameImg' => 'sub_smartphones.webp'],
                    ['name' => 'Accesorios', 'nameImg' => 'sub_accesorios.webp'],
                ],
            ],
            [
                'nameImg' => 'cat_technology.webp',
                'name' => 'Tecnologia',
                'subcategories' => [
                    ['name' => 'Portátiles', 'nameImg' => 'sub_portatiles.webp'],
                    ['name' => 'Tablets', 'nameImg' => 'sub_tablets.webp'],
                ],
            ],
            [
                'nameImg' => 'cat_car.webp',
                'name' => 'Coches',
                'subcategories' => [
                    ['name' => 'Eléctricos', 'nameImg' => 'sub_electricos.webp'],
                    ['name' => 'Usados', 'nameImg' => 'sub_usados.webp'],
                ],
            ],
            [
                'nameImg' => 'cat_services.webp',
                'name' => 'Servicios',
                'subcategories' => [
                    ['name' => 'Hogar', 'nameImg' => 'sub_hogar.webp'],
                    ['name' => 'Reparaciones', 'nameImg' => 'sub_reparaciones.webp'],
                ],
            ],
            // Puedes seguir añadiendo más categorías y subcategorías aquí...
        ];

        foreach ($categories as $cat) {
            // Crear la categoría principal
            $category = new Category();
            $category->name = $cat['name'];
            $category->nameImg = $cat['nameImg'];
            $category->categoria_id = null; // Es una categoría principal
            $category->save();

            // Crear las subcategorías asociadas, si existen
            if (isset($cat['subcategories'])) {
                foreach ($cat['subcategories'] as $subcat) {
                    $subcategory = new Category();
                    $subcategory->name = $subcat['name'];
                    $subcategory->nameImg = $subcat['nameImg'];
                    $subcategory->categoria_id = $category->id; // Relación con categoría principal
                    $subcategory->save();
                }
            }
        }
    }
}