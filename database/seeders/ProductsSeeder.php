<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'title' => 'Laptop MacBook Pro M1',
                'content' => 'MacBook Pro con chip M1, 16GB RAM, SSD 512GB.',
                'price' => 1200,
                'estado' => 'Nuevo',
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => true,
                'dimensionX' => 30,
                'dimensionY' => 20,
                'marca' => 'Apple',
                'color' => 'Plata',
                'image' => ['macbook-pro-m1-14-header.png']
            ],
            [
                'title' => 'Bicicleta de montaña Trek X-Caliber',
                'content' => 'Bicicleta en perfecto estado, ideal para montaña.',
                'price' => 650,
                'estado' => 'Usado',
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 18,
                'dimensionY' => 8,
                'marca' => 'Trek',
                'color' => 'Rojo',
                'image' => ['721188004_228995545_1024x576.webp', '720938388_228979212_1706x960.webp', 'dfs.jpg']
            ],
            [
                'title' => 'TV 4K LG OLED',
                'content' => 'Televisión de 55 pulgadas, en perfecto estado.',
                'price' => 800,
                'estado' => 'Nuevo',
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 55,
                'dimensionY' => 30,
                'marca' => 'LG',
                'color' => 'Negro',
                'image' => ['TVFY23_X85L_Primary-Image.webp', '500_333.jpeg']
            ],
            [
                'title' => 'Apple Watch SE 44mm',
                'content' => 'Reloj deportivo, resistente al agua.',
                'price' => 100,
                'estado' => 'Nuevo',
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 3,
                'dimensionY' => 3,
                'marca' => 'Apple',
                'color' => 'Negro',
                'image' => ['apple_watch_s10_case_46_aluminum_jetblack_Side.webp']
            ],
            [
                'title' => 'Cafetera Nespresso',
                'content' => 'Cafetera de cápsulas Nespresso en excelente estado.',
                'price' => 120,
                'estado' => 'Nuevo',
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 20,
                'dimensionY' => 12,
                'marca' => 'Nespresso',
                'color' => 'Negro',
                'image' => ['b-5-opciones-nespresso.jpg', '450_1000.jpeg']
            ],
            [
                'title' => 'Maceta de Cerámica Grande',
                'content' => 'Maceta de cerámica ideal para interiores.',
                'price' => 25,
                'estado' => 'Nuevo',
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 20,
                'dimensionY' => 20,
                'marca' => 'Cerámica Barcelona',
                'color' => 'Terracota',
                'image' => ['WhatsApp-Image-2023-10-04-at-17.04.16.jpeg']
            ],
            [
                'title' => 'Lámpara de Mesa Ikea',
                'content' => 'Lámpara de mesa de estilo moderno, nueva.',
                'price' => 30,
                'estado' => 'Nuevo',
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 10,
                'dimensionY' => 10,
                'marca' => 'Ikea',
                'color' => 'Blanco',
                'image' => ['lampara-ikea_d68c3650_240813101635_900x900.webp']
            ]
        ];
        foreach ($products as $productData) {
            // Crear el product en la base de datos
            $product = new Product();
            $product->title = $productData['title'];
            $product->content = $productData['content'];
            $product->price = $productData['price'];
            $product->estado = $productData['estado'];
            $product->toSend = $productData['toSend'];
            $product->isDeleted = $productData['isDeleted'];
            $product->isBoost = $productData['isBoost'];
            $product->dimensionX = $productData['dimensionX'];
            $product->dimensionY = $productData['dimensionY'];
            $product->marca = $productData['marca'];
            $product->color = $productData['color'];

            $product->save();

            foreach ($productData['default_image'] as $default_image) {
                $default_imagePath = storage_path('app/public/seed_images/products/' . $default_image);
                if (file_exists($default_imagePath)) {
                    $product->addMedia($default_imagePath)
                        ->preservingOriginal()
                        ->toMediaCollection('default_images');
                }

                // Agregar la imagen a Spatie Media Library
                $product->addMedia($imagePath)->toMediaCollection('images');
            }
        }
    }
}