<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingAddressTransactionResource extends JsonResource
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
            'transactions_id' => $this->transactions_id,
            'shipping_address_id' => $this->shipping_address_id,
            'status' => $this->status,
            'tracking_number' => $this->tracking_number,
            'created_at' => Date($this->created_at),
        ];
    }
}
