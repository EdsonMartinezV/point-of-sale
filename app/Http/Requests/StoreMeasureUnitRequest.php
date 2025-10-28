<?php

namespace App\Http\Requests;

use App\Models\MeasureUnit;
use Illuminate\Foundation\Http\FormRequest;

class StoreMeasureUnitRequest extends FormRequest
{
    public function authorize(): bool {
        return $this->user()->can('create', MeasureUnit::class);
    }

    public function rules(): array {
        return [
            'name' => ['required', 'string', 'max:255'],
            'abbreviation' => ['required', 'string', 'max:10'],
        ];
    }
}