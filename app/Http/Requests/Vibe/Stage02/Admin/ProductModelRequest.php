<?php

namespace App\Http\Requests\Vibe\Stage02\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'in:simple,configurable'],
            'main_image' => ['nullable', 'string', 'max:1000'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['nullable', 'string', 'max:1000'],
            'shared_attribute_ids' => ['nullable', 'array'],
            'shared_attribute_ids.*' => ['integer', 'exists:attributes,id'],
            'variant_attribute_ids' => ['nullable', 'array'],
            'variant_attribute_ids.*' => ['integer', 'exists:attributes,id'],
            'shared_attribute_values' => ['nullable', 'array'],
        ];
    }
}
