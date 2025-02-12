<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post_image;

class ImagePostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $images = [
            "90-angle-pUt42j4YvgY-unsplash.jpg",
            "blaz-erzetic-6BIfDxtRLCk-unsplash.jpg",
            "blaz-erzetic-FVO8Brah-yM-unsplash (1).jpg",
            "blaz-erzetic-UWKanmTQQBQ-unsplash.jpg",
            "daniel-korpai-zjjEXF9Igqc-unsplash.jpg",
            "harpal-singh-KuvEVL7lXYQ-unsplash.jpg",
            "hunter-newton-T7HiOZU3u_o-unsplash.jpg",
            "hunter-newton-yuMuWhz-PGI-unsplash.jpg",
            "kiran-ck-QMAHiU4Dd68-unsplash.jpg",
            "viktor-forgacs-w1ELNajqfwk-unsplash.jpg"
        ];
        
        $postId = 1; // ID del primer post
        $imagesPerPost = 3; // Cantidad de imágenes por post
        $totalImages = count($images);
        $index = 0; // Índice de imágenes
        
        while ($index < $totalImages) {
            for ($i = 0; $i < $imagesPerPost && $index < $totalImages; $i++) {
                $imagePost = new Post_image();
                $imagePost->title = $images[$index];
                $imagePost->POST_ID = $postId;
                $imagePost->save();
                $index++;
            }
            $postId++; // Pasar al siguiente post
        }
    }
}
