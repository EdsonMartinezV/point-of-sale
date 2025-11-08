<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows) {
        foreach ($rows as $row) {
            Product::create([
                'name' => $row['nombre'],
                'sold_by_retail' => $row['vendido_al_menudeo'] ?? false,
                'category_id' => $row['categoria_id'],
                'measure_unit_id' => $row['unidad_de_medida_id'],
                'retail_measure_unit_id' => $row['unidad_de_medida_menudeo_id'] ?? null,
            ]);
        }
    }

    public function rules(): array {
        return [
            'name' => ['required', 'string', 'max:255'],
            'sold_by_retail' => ['false', 'boolean'],
            'category_id' => ['required', 'exists:categories,id'],
            'measure_unit_id' => ['required', 'exists:measure_units,id'],
            'retail_measure_unit_id' => ['required_if:*.sold_by_retail,true', 'exists:categories,id'],
        ];
    }
}