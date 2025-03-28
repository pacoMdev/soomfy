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
                'title' => 'Volkswagen Golf GTI 2020',
                'content' => 'Golf GTI en excelente estado, 30.000km, mantenimiento al día.',
                'price' => 25000,
                'estado_id' => 2,
                'category_id' => 1,
                'user_id' => 1,
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => true,
                'dimensionX' => 450,
                'dimensionY' => 180,
                'marca' => 'Volkswagen',
                'color' => 'Negro',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'VW_Golf_8_GTI_Facelift_2024_Front.webp',
                ]
            ],
            [
                'title' => 'iPhone 14 Pro Max',
                'content' => 'Nuevo, precintado, 256GB de almacenamiento.',
                'price' => 1099,
                'estado_id' => 1,
                'category_id' => 2,
                'user_id' => 2,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => true,
                'dimensionX' => 15,
                'dimensionY' => 7,
                'marca' => 'Apple',
                'color' => 'Gris Espacial',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'iphone-14-pro-max-review-diez-meses-despues.webp',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'hq720.jpg',
                ]
            ],
            [
                'title' => 'Apartamento en el centro',
                'content' => '2 habitaciones, reformado, muy luminoso.',
                'price' => 180000,
                'estado_id' => 2,
                'category_id' => 3,
                'user_id' => 3,
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => true,
                'dimensionX' => 75,
                'dimensionY' => 65,
                'marca' => 'N/A',
                'color' => 'N/A',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'aticos-ventajas-precios-pisos.jpg',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'05-scaled-1.jpg',
                ]
            ],
            [
                'title' => 'Sofá moderno 3 plazas',
                'content' => 'Tapizado en tela, muy cómodo y como nuevo.',
                'price' => 450,
                'estado_id' => 2,
                'category_id' => 4,
                'user_id' => 1,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 220,
                'dimensionY' => 85,
                'marca' => 'IKEA',
                'color' => 'Gris',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'sofa-3-plazas-de-198-cm.jpg',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'sofa-de-ikea_2d970f40_240929193128_1280x720.webp',
                ]
            ],
            [
                'title' => 'Servicios de Jardinería',
                'content' => 'Mantenimiento de jardines y poda de árboles.',
                'price' => 25,
                'estado_id' => 1,
                'category_id' => 5,
                'user_id' => 2,
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 0,
                'dimensionY' => 0,
                'marca' => 'N/A',
                'color' => 'N/A',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'hogami-mantenimiento-y-cuidado-jardin-430x282-01.webp',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'JARDINERIA 2_5.jpg',
                ]
            ],
            [
                'title' => 'Bicicleta eléctrica Specialized',
                'content' => 'Motor Brose, batería 500Wh, poco uso.',
                'price' => 2800,
                'estado_id' => 2,
                'category_id' => 7,
                'user_id' => 3,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => true,
                'dimensionX' => 180,
                'dimensionY' => 110,
                'marca' => 'Specialized',
                'color' => 'Rojo',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'720938388_228979212_1706x960.webp',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'dfs.jpg',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'721188004_228995545_1024x576.webp',
                ]
            ],
            [
                'title' => 'Portátil Gaming MSI',
                'content' => 'RTX 3070, 32GB RAM, i7 11th gen.',
                'price' => 1200,
                'estado_id' => 3,
                'category_id' => 2,
                'user_id' => 1,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 35,
                'dimensionY' => 25,
                'marca' => 'MSI',
                'color' => 'Negro',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'00115215594944____2__1200x1200.jpg',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'8ce6215bb5261fc98142ce4833b2b7f462d86c9f.webp',
                ]
            ],
            [
                'title' => 'Clases particulares matemáticas',
                'content' => 'Profesor con 10 años de experiencia.',
                'price' => 20,
                'estado_id' => 1,
                'category_id' => 5,
                'user_id' => 2,
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 0,
                'dimensionY' => 0,
                'marca' => 'N/A',
                'color' => 'N/A',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'i5374685935.webp',
                ]
            ],
            [
                'title' => 'Mesa de comedor vintage',
                'content' => 'Madera maciza, extensible, 6-8 personas.',
                'price' => 350,
                'estado_id' => 3,
                'category_id' => 4,
                'user_id' => 3,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 160,
                'dimensionY' => 90,
                'marca' => 'Vintage',
                'color' => 'Marrón',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'D_NQ_NP_894606-MLM41131590465_032020-O.webp',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'i5421204561.webp',
                ]
            ],
            [
                'title' => 'Seat León FR 2019',
                'content' => '150cv, gasolina, techo panorámico.',
                'price' => 19500,
                'estado_id' => 2,
                'category_id' => 1,
                'user_id' => 1,
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => true,
                'dimensionX' => 430,
                'dimensionY' => 180,
                'marca' => 'Seat',
                'color' => 'Azul',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'precio-seat-leon-2019-201850153_1.jpg',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'Interior-del-SEAT-León-2019.jpg',
                ]
            ],
            [
                'title' => 'Set de pesas y mancuernas',
                'content' => 'Juego completo de 50kg ajustables.',
                'price' => 180,
                'estado_id' => 2,
                'category_id' => 7,
                'user_id' => 2,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 40,
                'dimensionY' => 30,
                'marca' => 'Decathlon',
                'color' => 'Negro',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'mancuerna-hexagonal-10kgs.jpg',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'Hex_Dumbbells_pair_60_lb_dd.webp',
                ]
            ],
            [
                'title' => 'Local comercial céntrico',
                'content' => '80m2, escaparate amplio, recién reformado.',
                'price' => 150000,
                'estado_id' => 2,
                'category_id' => 3,
                'user_id' => 3,
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => true,
                'dimensionX' => 80,
                'dimensionY' => 80,
                'marca' => 'N/A',
                'color' => 'N/A',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'ivan_cotado_tu_vision_ii_optica_ponferrada_21.jpg',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'eyJidWNrZXQiOiJwcm.webp',
                ]
            ],
            [
                'title' => 'Lavadora Samsung EcoBubble',
                'content' => '9kg, 1400rpm, eficiencia A+++.',
                'price' => 380,
                'estado_id' => 3,
                'category_id' => 4,
                'user_id' => 1,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 60,
                'dimensionY' => 85,
                'marca' => 'Samsung',
                'color' => 'Blanco',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'lavadora-ecobubble.webp',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'5a25fce640075e4b5e8147e9a702eb43de0e932a.jpg.webp',
                ]
            ],
            [
                'title' => 'Monitor Gaming 32" Curvo',
                'content' => '165Hz, 1ms, QHD, FreeSync Premium.',
                'price' => 299,
                'estado_id' => 1,
                'category_id' => 2,
                'user_id' => 2,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 70,
                'dimensionY' => 45,
                'marca' => 'AOC',
                'color' => 'Negro',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'maxresdefault.jpg',
                ]
            ],
            [
                'title' => 'Servicios de fotografía',
                'content' => 'Bodas, eventos, books profesionales.',
                'price' => 200,
                'estado_id' => 1,
                'category_id' => 5,
                'user_id' => 3,
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => true,
                'dimensionX' => 0,
                'dimensionY' => 0,
                'marca' => 'N/A',
                'color' => 'N/A',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'principal_blog_950-11.jpg',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'foto_01.jpg',
                ]
            ],
            [
                'title' => 'Cinta de correr profesional',
                'content' => 'Motor 3.5HP, programas automáticos.',
                'price' => 750,
                'estado_id' => 2,
                'category_id' => 7,
                'user_id' => 1,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 180,
                'dimensionY' => 85,
                'marca' => 'NordicTrack',
                'color' => 'Negro',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'3370.jpg',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'correr-cinta-casa-768x512.webp',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'3371.jpg',
                ]
            ],
            [
                'title' => 'Honda CBR 600RR',
                'content' => '2018, 15.000km, extras incluidos.',
                'price' => 8500,
                'estado_id' => 3,
                'category_id' => 1,
                'user_id' => 2,
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => true,
                'dimensionX' => 210,
                'dimensionY' => 110,
                'marca' => 'Honda',
                'color' => 'Rojo/Negro',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'HONDA-CBR600-3.jpeg',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'2024-Honda-CBR600RR-04.webp',
                ]
            ],
            [
                'title' => 'iPad Pro 12.9 2022',
                'content' => 'M2, 256GB, WiFi + Cellular.',
                'price' => 950,
                'estado_id' => 2,
                'category_id' => 2,
                'user_id' => 3,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 28,
                'dimensionY' => 21,
                'marca' => 'Apple',
                'color' => 'Plata',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'Apple_iPad_12.9_2022_Outdoor_4409.jpg',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'74d09d7be05e12a9d273022593cd4e3327eee388.jpg',
                ]
            ],
            [
                'title' => 'Piso ático con terraza',
                'content' => '3 habitaciones, terraza 40m2, parking.',
                'price' => 295000,
                'estado_id' => 2,
                'category_id' => 3,
                'user_id' => 1,
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => true,
                'dimensionX' => 100,
                'dimensionY' => 90,
                'marca' => 'N/A',
                'color' => 'N/A',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'show.jpg',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'eyJrZXkiOiJhdGljb19kZV.jpeg',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'1256080428.jpg',
                ]
            ],
            [
                'title' => 'Nevera americana Samsung',
                'content' => 'Dispensador agua/hielo, panel digital.',
                'price' => 850,
                'estado_id' => 3,
                'category_id' => 4,
                'user_id' => 2,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 90,
                'dimensionY' => 178,
                'marca' => 'Samsung',
                'color' => 'Inox',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'i4601065563.webp',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'i5480647155.webp',
                ]
            ],
            [
                'title' => 'Profesor nativo de inglés',
                'content' => 'Clases online o presenciales.',
                'price' => 25,
                'estado_id' => 1,
                'category_id' => 5,
                'user_id' => 3,
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 0,
                'dimensionY' => 0,
                'marca' => 'N/A',
                'color' => 'N/A',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'i5479695805.webp',
                ]
            ],
            [
                'title' => 'Raqueta Wilson Pro Staff',
                'content' => 'Modelo RF97, poco uso.',
                'price' => 180,
                'estado_id' => 2,
                'category_id' => 7,
                'user_id' => 1,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 70,
                'dimensionY' => 30,
                'marca' => 'Wilson',
                'color' => 'Negro',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'PS97S-R1.webp',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'Image1.webp',
                ]
            ],
            [
                'title' => 'Diseñador gráfico freelance',
                'content' => 'Logos, branding, web design.',
                'price' => 35,
                'estado_id' => 1,
                'category_id' => 6,
                'user_id' => 2,
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => true,
                'dimensionX' => 0,
                'dimensionY' => 0,
                'marca' => 'N/A',
                'color' => 'N/A',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'disenador-grafico-freelance.jpg',
                ]
            ],
            [
                'title' => 'Consola PS5 Digital',
                'content' => 'Nueva, sin estrenar, 2 mandos.',
                'price' => 450,
                'estado_id' => 1,
                'category_id' => 2,
                'user_id' => 3,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => true,
                'dimensionX' => 40,
                'dimensionY' => 25,
                'marca' => 'Sony',
                'color' => 'Blanco',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'ps5-playstation-5-2969058.webp',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'ps5-2692615.webp',
                ]
            ],
            [
                'title' => 'Programador Full Stack',
                'content' => 'Desarrollo web y aplicaciones móviles.',
                'price' => 45,
                'estado_id' => 1,
                'category_id' => 6,
                'user_id' => 1,
                'toSend' => false,
                'isDeleted' => false,
                'isBoost' => true,
                'dimensionX' => 0,
                'dimensionY' => 0,
                'marca' => 'N/A',
                'color' => 'N/A',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'¿Cuanto-gana-un-programador-full-stack_-1200x900.png',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'what-is-a-full-stack-developer-1024x512.png',
                ]
            ],
            [
                'title' => 'Mesa de ping pong profesional',
                'content' => 'Plegable, incluye red y raquetas.',
                'price' => 295,
                'estado_id' => 2,
                'category_id' => 7,
                'user_id' => 2,
                'toSend' => true,
                'isDeleted' => false,
                'isBoost' => false,
                'dimensionX' => 274,
                'dimensionY' => 152,
                'marca' => 'Cornilleau',
                'color' => 'Azul',
                'images' => [
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'pingpong18mmtableroinped-430x323.jpeg',
                    DIRECTORY_SEPARATOR.'seed_images'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.'images.jpeg',
                ]
            ]
        ];

        foreach ($products as $productData) {
            $product = new Product();
            $product->title = $productData['title'];
            $product->content = $productData['content'];
            $product->price = $productData['price'];
            $product->estado_id = $productData['estado_id'];
            $product->category_id = $productData['category_id'];
            $product->user_id = $productData['user_id'];
            $product->toSend = $productData['toSend'];
            $product->isDeleted = $productData['isDeleted'];
            $product->isBoost = $productData['isBoost'];
            $product->dimensionX = $productData['dimensionX'];
            $product->dimensionY = $productData['dimensionY'];
            $product->marca = $productData['marca'];
            $product->color = $productData['color'];
            // Imagenes subidas por media (spatieMedia)
            if (isset($productData['images'])) {
                foreach ($productData['images'] as $imagePath) {
                    $product->addMedia(storage_path($imagePath))->preservingOriginal()->toMediaCollection('images');
                }
            }
            $product->save();
        }
    }
}