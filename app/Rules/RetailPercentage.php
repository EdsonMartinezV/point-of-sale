<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class RetailPercentage implements ValidationRule, DataAwareRule
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
        $index = explode('.', $attribute)[1];
        if (!$this->data['sale_items'][$index]['is_retail_sale'] && $value == 'retail_percentage') {
            $fail('Seleccione un margen de ventas.', null);
        }
    }
}
