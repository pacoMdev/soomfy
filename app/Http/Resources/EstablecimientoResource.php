<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EstablecimientoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'zip' => $this->zip,
            'poblacion' => $this->poblacion,
            'ciudad' => $this->ciudad,
            'telefono' => $this->isToSend,
            'email' => $this->email,
            'nombre_comercial' => $this->nombre_comercial,
            'created_at' => Date($this->created_at),
        ];
    }
}
