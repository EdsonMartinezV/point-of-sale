<?php

namespace App\Http\Requests;

use App\Models\Purchase;
use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
{
    public function authorize() {
        return $this->user()->can('create', Purchase::class);
    }

    public function rules() {
        return [
            'total' => ['required', 'numeric', 'min:1'],
            'provider_id' => ['required', 'exists:providers,id'],
            /* PURCHASE ITEMS */
            'purchase_items' => ['required', 'array'],
            'purchase_items.*.quantity' => ['required', 'integer', 'min:1'],
            'purchase_items.*.product_id' => ['required', 'exists:products,id'],
            /* PRICE MODIFICATIONS */
            'purchase_items.*.sold_by_retail' => ['required', 'boolean'],
            'purchase_items.*.retail_units_per_box' => ['required', 'integer', 'min:1'],
            'purchase_items.*.cost_price' => ['required', 'numeric', 'min:1'],
            'purchase_items.*.first_wholesale_percentage' => ['required', 'integer', 'min:1', 'max:100'],
            'purchase_items.*.second_wholesale_percentage' => ['required', 'integer', 'min:1', 'max:100'],
            'purchase_items.*.third_wholesale_percentage' => ['required', 'integer', 'min:1', 'max:100'],
            'purchase_items.*.retail_percentage' => ['nullable', 'required_if:sold_by_retail,true', 'integer', 'min:1', 'max:100'],
            /* PRODUCTS FIELDS ALONG PREVIOUS FIELDS */
        ];
    }

    public function messages() {
        return [
            'purchase_items.*.retail_percentage.required_if' => '.El porcentaje de venta al menudeo es obligatorio cuando se vende al menudeo.',
        ];
    }
}