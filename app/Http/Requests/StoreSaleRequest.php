<?php

namespace App\Http\Requests;

use App\Percentages;
use App\Rules\HasStock;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSaleRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'client' => ['required', 'string', 'max:255'],
            'total' => ['required', 'numeric', 'min:0'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'change_amount' => ['required', 'numeric', 'min:0'],
            /* SALE ITEMS */
            'sale_items' => ['required', 'array'],
            'sale_items.*.quantity' => ['required', 'integer', 'min:1', new HasStock],
            'sale_items.*.is_retail_sale' => ['required', 'boolean'],
            'sale_items.*.selected_percentage' => ['required', Rule::enum(Percentages::class)],
            'sale_items.*.product_id' => ['required', 'exists:products,id'],
            'sale_items.*.price_modification_id' => ['required', 'exists:price_modifications,id'],
        ];
    }
}