<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array{
        return [
            'id' => $this->id,
            'client' => $this->client,
            'total' => $this->total,
            'paid_amount' => $this->paid_amount,
            'change_amount' => $this->change_amount,
            'created_by' => $this->user->nickname,
            'created_at' => new Carbon($this->created_at)->toDateTimeString('minute'),
        ];
    }
}
