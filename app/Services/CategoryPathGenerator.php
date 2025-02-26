<?php

namespace App\Services;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CategoryPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        // Solo aplica a la colección 'category_image'
        if ($media->collection_name === 'category_image') {
            return 'categories/' . $media->model_id . '/';
        }

        // Para otras colecciones, usa la ruta por defecto
        return $media->model_id . '/';
    }

    public function getPathForConversions(Media $media): string
    {
        if ($media->collection_name === 'category_image') {
            return 'categories/' . $media->model_id . '/conversions/';
        }

        return $media->model_id . '/conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        if ($media->collection_name === 'category_image') {
            return 'categories/' . $media->model_id . '/responsive-images/';
        }

        return $media->model_id . '/responsive-images/';
    }
}