<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize() {
        return $this->user()->can('create', Product::class);
    }

    public function rules() {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:products'],
            /* 'sold_by_retail' => ['required', 'boolean'], */
            'category_id' => ['required', 'exists:categories,id'],
            'measure_unit_id' => ['required', 'exists:measure_units,id'],
            'retail_measure_unit_id' => ['required', 'exists:measure_units,id'],
        ];
    }

    public function messages() {
        return [
            'retail_measure_unit_id.required_if' => 'La unidad de medida al menudeo es obligatoria cuando el producto se vende al menudeo.',
        ];
    }
}