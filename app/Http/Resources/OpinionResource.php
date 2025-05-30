<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OpinionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this ->id,
            'title' => $this ->title,
            'description' => $this ->destription,
            'calification' => $this ->calification,
            'token' => $this ->token,
            'product_id' => $this ->product_id,
            'user_id' => $this ->user_id,
            'created_at' => Date($this ->created_at),
        ];
    }
}
