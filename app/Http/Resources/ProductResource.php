<?php

namespace App\Http\Resources;

use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //if no resize image
        try {
            $resized_image = $this->getMedia('*');//[0]->getUrl('resized-image');
        } catch (Exception $e) {
            $resized_image="";
        }
        $esFavorito = false;
        if (Auth::check()) { // Verificamos que hay usuario autenticado
            $user = Auth::user();
            if ($user->favoritos()->where('product_id', $this->id)->exists()) {
                $esFavorito = true;
            }
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'category' => $this->category,
            'user' => $this->user,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'estado' => $this->estado,
            'toSend' => $this->toSend,
            'isBoost' => $this->isBoost,
            'isDeleted' => $this->isDeleted,
            'marca' => $this->marca,
            'color' => $this->color,
            'products' => $this->categories,
            'original_image' => count($this->getMedia('*')) > 0 ? $this->getMedia('*')[0]->getUrl() : null,
            'resized_image' => $resized_image,
            'esFavorito' => $esFavorito,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
