<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceModificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array{
        return [
            'id' => $this->id,
            'is_current' => boolval($this->is_current),
            'sold_by_retail' => boolval($this->sold_by_retail),
            'retail_units_per_box' => $this->retail_units_per_box,
            'remaining_stock' => $this->remaining_stock,
            'retail_remaining_stock' => $this->retail_remaining_stock,
            'cost_price' => $this->cost_price,
            'first_wholesale_percentage' => $this->first_wholesale_percentage,
            'second_wholesale_percentage' => $this->second_wholesale_percentage,
            'third_wholesale_percentage' => $this->third_wholesale_percentage,
            'retail_percentage' => $this->retail_percentage,
        ];
    }
}
