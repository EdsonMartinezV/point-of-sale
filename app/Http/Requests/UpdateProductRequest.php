<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize() {
        return $this->user()->can('update', Product::class);
    }

    public function rules() {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'sold_by_retail' => ['nullable', 'boolean'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'measure_unit_id' => ['nullable', 'exists:measure_units,id'],
            'retail_measure_unit_id' => ['required_if:sold_by_retail,true', 'exists:measure_units,id'],
        ];
    }
}