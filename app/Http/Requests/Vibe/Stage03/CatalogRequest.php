<?php

namespace App\Http\Requests\Vibe\Stage03;

use Illuminate\Foundation\Http\FormRequest;

class CatalogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'in:simple,configurable'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
        ];
    }
}
