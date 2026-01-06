<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class cartsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'carts' => [
                'id' => $this->id,
                'jumlah' => $this->jumlah,
            ],
            'products' => [
                'name' => $this->product_name,
                'gambar' => $this->product_gambar ?? ''
            ],
            'store' => [
                'name' => $this->store_name
            ],
            'variant' => [
                'id' => $this->variant_id,
                'option' => $this->variant_option_1 . " " . $this->variant_option_2,
                'price' => $this->variant_price,
                'jumlah' => $this->variant_jumlah,
            ],
            $this->whenLoaded('variants' , function ($items) {
                return $items;
            })
        ];
    }
}
