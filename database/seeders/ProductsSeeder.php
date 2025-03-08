<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'title' => 'Laptop MacBook Pro M1',
                'content' => 'MacBook Pro con chip M1, 16GB RAM, SSD 512GB.',
                'price' => 1200,
                'estado_id' => 1, // ID del estado "Nuevo"
                'category_id' => 2, // ID de la categoría "Tecnología"
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => true,
                'dimensionX' => 30,
                'dimensionY' => 20,
                'marca' => 'Apple',
                'color' => 'Plata',
            ],
            [
                'title' => 'Bicicleta de montaña Trek X-Caliber',
                'content' => 'Bicicleta en perfecto estado, ideal para montaña.',
                'price' => 650,
                'estado_id' => 2, // ID del estado "Usado"
                'category_id' => 7, // ID de la categoría "Deporte"
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 18,
                'dimensionY' => 8,
                'marca' => 'Trek',
                'color' => 'Rojo',
            ],
            [
                'title' => 'TV 4K LG OLED',
                'content' => 'Televisión de 55 pulgadas, en perfecto estado.',
                'price' => 800,
                'estado_id' => 1, // ID del estado "Nuevo"
                'category_id' => 2, // ID de la categoría "Tecnología"
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 55,
                'dimensionY' => 30,
                'marca' => 'LG',
                'color' => 'Negro',
            ],
            [
                'title' => 'Apple Watch SE 44mm',
                'content' => 'Reloj deportivo, resistente al agua.',
                'price' => 100,
                'estado_id' => 1, // ID del estado "Nuevo"
                'category_id' => 2, // ID de la categoría "Tecnología"
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 3,
                'dimensionY' => 3,
                'marca' => 'Apple',
                'color' => 'Negro',
            ],
            [
                'title' => 'Cafetera Nespresso',
                'content' => 'Cafetera de cápsulas Nespresso en excelente estado.',
                'price' => 120,
                'estado_id' => 1, // ID del estado "Nuevo"
                'category_id' => 4, // ID de la categoría "Hogar"
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 20,
                'dimensionY' => 12,
                'marca' => 'Nespresso',
                'color' => 'Negro',
            ],
            [
                'title' => 'Maceta de Cerámica Grande',
                'content' => 'Maceta de cerámica ideal para interiores.',
                'price' => 25,
                'estado_id' => 1, // ID del estado "Nuevo"
                'category_id' => 4, // ID de la categoría "Hogar"
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 20,
                'dimensionY' => 20,
                'marca' => 'Cerámica Barcelona',
                'color' => 'Terracota',
            ],
            [
                'title' => 'Lámpara de Mesa Ikea',
                'content' => 'Lámpara de mesa de estilo moderno, nueva.',
                'price' => 30,
                'estado_id' => 1, // ID del estado "Nuevo"
                'category_id' => 4, // ID de la categoría "Hogar"
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 10,
                'dimensionY' => 10,
                'marca' => 'Ikea',
                'color' => 'Blanco',
            ]
        ];

        foreach ($products as $productData) {
            $product = new Product();
            $product->title = $productData['title'];
            $product->content = $productData['content'];
            $product->price = $productData['price'];
            $product->estado_id = $productData['estado_id'];
            $product->category_id = $productData['category_id'];
            $product->toSend = $productData['toSend'];
            $product->isDeleted = $productData['isDeleted'];
            $product->isBoost = $productData['isBoost'];
            $product->dimensionX = $productData['dimensionX'];
            $product->dimensionY = $productData['dimensionY'];
            $product->marca = $productData['marca'];
            $product->color = $productData['color'];
            $product->save();
        }
    }
}