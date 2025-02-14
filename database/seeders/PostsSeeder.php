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
                'title' => 'Laptop MacBook Pro M1',
                'content' => 'MacBook Pro con chip M1, 16GB RAM, SSD 512GB.',
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
                'image' => ['macbook-pro-m1-14-header.png']
            ],
            [
                'title' => 'Bicicleta de montaña Trek X-Caliber',
                'content' => 'Bicicleta en perfecto estado, ideal para montaña.',
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
                'image' => ['721188004_228995545_1024x576.webp', '720938388_228979212_1706x960.webp', 'dfs.jpg']
            ],
            [
                'title' => 'TV 4K LG OLED',
                'content' => 'Televisión de 55 pulgadas, en perfecto estado.',
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
                'image' => ['TVFY23_X85L_Primary-Image.webp', '500_333.jpeg']
            ],
            [
                'title' => 'Apple Watch SE 44mm',
                'content' => 'Reloj deportivo, resistente al agua.',
                'price' => 100,
                'estado' => 'Nuevo',
                'latitude' => 41.3778, // Coordenada en Barcelona
                'longitude' => 2.1899,
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
                'latitude' => 41.3833, // Coordenada en Barcelona
                'longitude' => 2.1875,
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
                'latitude' => 41.3812, // Coordenada en Barcelona
                'longitude' => 2.1935,
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
                'latitude' => 41.3798, // Coordenada en Barcelona
                'longitude' => 2.1884,
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
        foreach ($posts as $postData) {
            // Crear el post en la base de datos
            $post = new Post();
            $post -> title = $postData['title'];
            $post -> content = $postData['content'];
            $post -> price = $postData['price'];
            $post -> estado = $postData['estado'];
            $post -> latitude = $postData['latitude'];
            $post -> longitude = $postData['longitude'];
            $post -> toSend = $postData['toSend'];
            $post -> isDeleted = $postData['isDeleted'];
            $post -> isBoost = $postData['isBoost'];
            $post -> dimensionX = $postData['dimensionX'];
            $post -> dimensionY = $postData['dimensionY'];
            $post -> marca = $postData['marca'];
            $post -> color = $postData['color'];

            $post -> save();
            
            // $post = Post::create([
            //     'title' => $postData['title'],
            //     'content' => $postData['content'],
            // ]);

            // // Copiar la imagen a storage si no existe
            // $imagePath = storage_path('app/public/posts/' . $postData['image']);
            // if (!file_exists($imagePath)) {
            //     copy(public_path('seed_images/' . $postData['image']), $imagePath);
            // }

            // // Agregar la imagen a Spatie Media Library
            // $post->addMedia($imagePath)->toMediaCollection('images');

            foreach($postData['image'] as $image){
                // Copiar la imagen a storage si no existe
                // hay que hacer un 
                $imagePath = storage_path('app'. DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . $image);
                if (!file_exists($imagePath)) {
                    copy(public_path('seed_images' . DIRECTORY_SEPARATOR . $image), $imagePath);
                }
    
                // Agregar la imagen a Spatie Media Library
                $post->addMedia($imagePath)->toMediaCollection('images');
            }
        }

    }
}
