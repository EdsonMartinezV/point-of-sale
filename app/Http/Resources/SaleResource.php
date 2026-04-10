<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use NumberFormatter;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array{
        $numberFormatter = new NumberFormatter('es', NumberFormatter::SPELLOUT, NumberFormatter::PARSE_INT_ONLY);
        $totalParts = explode('.', (string)$this->total);
        $integerPart = $numberFormatter->format((int)$totalParts[0]);
        $decimalPart = $totalParts[1] ?? '00';

        $month = str_pad((new Carbon($this->created_at))->month, 2, '0', STR_PAD_LEFT);
        $year = str_pad(substr((new Carbon($this->created_at))->year, -2), 2, '0', STR_PAD_LEFT);

        return [
            'id' => $this->id,
            'client' => $this->client,
            'total' => $this->total,
            'spelled_total' => strtoupper($integerPart . ' ' . $decimalPart . '/100 MXN'),
            'paid_amount' => $this->paid_amount,
            'change_amount' => $this->change_amount,
            'created_by' => $this->user->nickname,
            'created_at' => new Carbon($this->created_at)->toDateTimeString('minute'),
            'sale_items' => SaleItemResource::collection($this->whenLoaded('saleItems')),
            'total_articles' => $this->saleItems->sum('quantity'),
            'folio' => $month . $year . str_pad(substr($this->id, -6), 6, '0', STR_PAD_LEFT),
        ];
    }
}
