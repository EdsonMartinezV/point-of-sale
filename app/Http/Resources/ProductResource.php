<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array{
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sold_by_retail' => $this->sold_by_retail,
            'retail_units_per_box' => $this->retail_units_per_box,
            'cost_price' => $this->cost_price,
            'first_wholesale_percentage' => $this->first_wholesale_percentage,
            'second_wholesale_percentage' => $this->second_wholesale_percentage,
            'third_wholesale_percentage' => $this->third_wholesale_percentage,
            'retail_percentage' => $this->retail_percentage,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'measure_unit' => new MeasureUnitResource($this->whenLoaded('measureUnit')),
            'retail_measure_unit' => new MeasureUnitResource($this->whenLoaded('retailMeasureUnit')),
            'current_price_modification' => $this->current_price_modification->id ?? null,
        ];
    }
}
