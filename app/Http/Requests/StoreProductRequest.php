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
            'name' => ['required', 'string', 'max:255'],
            'sold_by_retail' => ['required', 'boolean'],
            'category_id' => ['required', 'exists:categories,id'],
            'measure_unit_id' => ['required', 'exists:measure_units,id'],
            'retail_measure_unit_id' => ['required_if:sold_by_retail,true', 'exists:measure_units,id'],
        ];
    }
}