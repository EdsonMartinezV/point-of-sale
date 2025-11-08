<?php

namespace App\Rules;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class HasStock implements DataAwareRule, ValidationRule
{
    protected $data = [];

    public function setData(array $data): static{
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void{
        // $data = request()->all();
        $index = explode('.', $attribute)[1];
        $product = Product::find($this->data['sale_items'][$index]['product_id']);

        if ($this->data['sale_items'][$index]['is_retail_sale']) {
            if ($value > $product->total_retail_remaining_stock) {
                $fail('No hay suficiente stock minorista para este producto.');
            }
        } else {
            if ($value > $product->stock) {
                $fail('No hay suficientes unidades.');
            }
        }
    }
}
