<?php

namespace App\Http\Requests\Vibe\Stage02\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VariantStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'string', 'max:1000'],
            'attribute_values' => ['required', 'array', 'min:1'],
        ];
    }
}
