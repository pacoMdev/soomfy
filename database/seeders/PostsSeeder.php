<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Smartphone Samsung Galaxy S21',
                'description' => 'Teléfono en excelente estado, sin rayones.',
                'price' => 450,
                'estado' => 'Usado',
                'latitude' => 41.3774, // Coordenada en Barcelona
                'longitude' => 2.1927,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 15,
                'dimensionY' => 7,
                'marca' => 'Samsung',
                'color' => 'Negro',
                'category_id' => 1
            ],
            [
                'title' => 'Laptop MacBook Pro M1',
                'description' => 'MacBook Pro con chip M1, 16GB RAM, SSD 512GB.',
                'price' => 1200,
                'estado' => 'Nuevo',
                'latitude' => 41.4027, // Coordenada en Barcelona
                'longitude' => 2.1900,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => true,
                'dimensionX' => 30,
                'dimensionY' => 20,
                'marca' => 'Apple',
                'color' => 'Plata',
                'category_id' => 2
            ],
            [
                'title' => 'Bicicleta de montaña Trek X-Caliber',
                'description' => 'Bicicleta en perfecto estado, ideal para montaña.',
                'price' => 650,
                'estado' => 'Usado',
                'latitude' => 41.3775, // Coordenada en Barcelona
                'longitude' => 2.1678,
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 18,
                'dimensionY' => 8,
                'marca' => 'Trek',
                'color' => 'Rojo',
                'category_id' => 12
            ],
            [
                'title' => 'TV 4K LG OLED',
                'description' => 'Televisión de 55 pulgadas, en perfecto estado.',
                'price' => 800,
                'estado' => 'Nuevo',
                'latitude' => 41.3851, // Coordenada en Barcelona
                'longitude' => 2.1734,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 55,
                'dimensionY' => 30,
                'marca' => 'LG',
                'color' => 'Negro',
                'category_id' => 2
            ],
            [
                'title' => 'Cámara Sony Alpha 7 III',
                'description' => 'Cámara profesional de alta calidad.',
                'price' => 1500,
                'estado' => 'Usado',
                'latitude' => 41.3920, // Coordenada en Barcelona
                'longitude' => 2.1580,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => true,
                'dimensionX' => 10,
                'dimensionY' => 7,
                'marca' => 'Sony',
                'color' => 'Negro',
                'category_id' => 2
            ],
            [
                'title' => 'Silla Gaming DXRacer',
                'description' => 'Silla ergonómica para juegos en perfecto estado.',
                'price' => 200,
                'estado' => 'Nuevo',
                'latitude' => 41.3805, // Coordenada en Barcelona
                'longitude' => 2.1893,
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 10,
                'dimensionY' => 20,
                'marca' => 'DXRacer',
                'color' => 'Negro',
                'category_id' => 9
            ],
            [
                'title' => 'Patinete Eléctrico Xiaomi M365',
                'description' => 'Patinete eléctrico en estado como nuevo.',
                'price' => 350,
                'estado' => 'Usado',
                'latitude' => 41.3794, // Coordenada en Barcelona
                'longitude' => 2.1910,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => true,
                'dimensionX' => 12,
                'dimensionY' => 12,
                'marca' => 'Xiaomi',
                'color' => 'Blanco',
                'category_id' => 2
            ],
            [
                'title' => 'Reloj Casio G-Shock',
                'description' => 'Reloj deportivo, resistente al agua.',
                'price' => 100,
                'estado' => 'Nuevo',
                'latitude' => 41.3778, // Coordenada en Barcelona
                'longitude' => 2.1899,
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 3,
                'dimensionY' => 3,
                'marca' => 'Casio',
                'color' => 'Negro',
                'category_id' => 7
            ],
            [
                'title' => 'Coche Eléctrico Infantil',
                'description' => 'Coche eléctrico para niños con mando remoto.',
                'price' => 250,
                'estado' => 'Nuevo',
                'latitude' => 41.3800, // Coordenada en Barcelona
                'longitude' => 2.1933,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 50,
                'dimensionY' => 40,
                'marca' => 'Xtoys',
                'color' => 'Rojo',
                'category_id' => 14
            ],
            [
                'title' => 'Bici Eléctrica BH Easy Move',
                'description' => 'Bicicleta eléctrica ideal para moverse por la ciudad.',
                'price' => 950,
                'estado' => 'Nuevo',
                'latitude' => 41.3820, // Coordenada en Barcelona
                'longitude' => 2.1804,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 17,
                'dimensionY' => 7,
                'marca' => 'BH',
                'color' => 'Azul',
                'category_id' => 12
            ],
            [
                'title' => 'Tostadora Philips',
                'description' => 'Tostadora de 2 ranuras en perfecto estado.',
                'price' => 40,
                'estado' => 'Nuevo',
                'latitude' => 41.3744, // Coordenada en Barcelona
                'longitude' => 2.1702,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 10,
                'dimensionY' => 7,
                'marca' => 'Philips',
                'color' => 'Blanco',
                'category_id' => 14
            ],
            [
                'title' => 'Cafetera Nespresso',
                'description' => 'Cafetera de cápsulas Nespresso en excelente estado.',
                'price' => 120,
                'estado' => 'Nuevo',
                'latitude' => 41.3833, // Coordenada en Barcelona
                'longitude' => 2.1875,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 20,
                'dimensionY' => 12,
                'marca' => 'Nespresso',
                'color' => 'Negro',
                'category_id' => 14
            ],
            [
                'title' => 'Maceta de Cerámica Grande',
                'description' => 'Maceta de cerámica ideal para interiores.',
                'price' => 25,
                'estado' => 'Nuevo',
                'latitude' => 41.3812, // Coordenada en Barcelona
                'longitude' => 2.1935,
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 20,
                'dimensionY' => 20,
                'marca' => 'Cerámica Barcelona',
                'color' => 'Terracota',
                'category_id' => 11
            ],
            [
                'title' => 'Lámpara de Mesa Ikea',
                'description' => 'Lámpara de mesa de estilo moderno, nueva.',
                'price' => 30,
                'estado' => 'Nuevo',
                'latitude' => 41.3798, // Coordenada en Barcelona
                'longitude' => 2.1884,
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 10,
                'dimensionY' => 10,
                'marca' => 'Ikea',
                'color' => 'Blanco',
                'category_id' => 9
            ],
            [
                'title' => 'Lentes de Sol Ray-Ban',
                'description' => 'Lentes de sol originales, sin rayaduras.',
                'price' => 150,
                'estado' => 'Nuevo',
                'latitude' => 41.3823, // Coordenada en Barcelona
                'longitude' => 2.1808,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => true,
                'dimensionX' => 5,
                'dimensionY' => 5,
                'marca' => 'Ray-Ban',
                'color' => 'Negro',
                'category_id' => 14
            ]
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }

    }
}
