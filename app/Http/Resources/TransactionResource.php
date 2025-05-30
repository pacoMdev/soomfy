<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'userSeller_id' => $this->userSeller_id,
            'userBuyer_id' => $this->userBuyer_id,
            'product_id' => $this->product_id,
            'initialPrice' => $this->initialPrice,
            'finalPrice' => $this->finalPirce,
            'isToSend' => $this->isToSend,
            'isRegated' => $this->isRegated,
        ];
    }
}
