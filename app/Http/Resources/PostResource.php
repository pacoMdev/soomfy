<?php

namespace App\Http\Resources;

use Exception;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'estado' => $this->estado,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'toSend' => $this->toSend,
            'isBoost' => $this->isBoost,
            'isDeleted' => $this->isDeleted,
            'marca' => $this->marca,
            'color' => $this->color,
            'categories' => $this->categories,
            'content' => $this->content,
            // 'original_image' => count($this->getMedia('*')) > 0 ? $this->getMedia('*')[0]->getUrl() : null,
            'resized_image' => $resized_image,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
