<?php

namespace App\Http\Requests;

use App\Models\Purchase;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseRequest extends FormRequest
{
    public function authorize() {
        return $this->user()->can('update', Purchase::class);
    }

    public function rules() {
        return [
            'total' => ['nullable', 'numeric', 'min:0'],
            'provider_id' => ['nullable', 'exists:providers,id'],
            /* PURCHASE ITEMS */
            'purchase_items' => ['nullable', 'array'],
            'purchase_items.*.id' => ['required', 'integer', 'exists:purchase_items,id'],
            'purchase_items.*.quantity' => ['nullable', 'integer', 'min:1'],
            'purchase_items.*.product_id' => ['nullable', 'exists:products,id'],
            /* PRICE MODIFICATIONS */
            'purchase_items.*.sold_by_retail' => ['nullable', 'boolean'],
            'purchase_items.*.retail_units_per_box' => ['nullable', 'integer', 'min:1'],
            'purchase_items.*.cost_price' => ['required', 'numeric', 'min:0'],
            'purchase_items.*.first_wholesale_percentage' => ['nullable', 'numeric', 'min:0'],
            'purchase_items.*.second_wholesale_percentage' => ['nullable', 'numeric', 'min:0'],
            'purchase_items.*.third_wholesale_percentage' => ['nullable', 'numeric', 'min:0'],
            'purchase_items.*.retail_percentage' => ['required_if:sold_by_retail,true', 'numeric', 'min:0'],
        ];
    }

    public function messages() {
        return [
            'purchase_items.*.retail_percentage.required_if' => '.El porcentaje de venta al menudeo es obligatorio cuando se vende al menudeo.',
        ];
    }
}