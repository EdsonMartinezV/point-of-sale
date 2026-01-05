<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array{
        return [
            'id' => $this->id,
            'total' => $this->total,
            'created_by' => $this->user->name,
            'provider' => new ProviderResource($this->whenLoaded('provider')),
            'purchase_items' => PurchaseItemResource::collection($this->whenLoaded('purchaseItems')),
            'created_at' => Carbon::createFromTimestamp($this->created_at)->toDateTimeString('minute'),
        ];
    }
}
