<?php

namespace App\Http\Requests\Vibe\Stage07;

use Illuminate\Foundation\Http\FormRequest;

class PricingQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'correlation_id' => ['nullable', 'string', 'max:100'],
            'city_id' => ['nullable', 'integer', 'exists:city,id'],
            'coupon_id' => ['nullable', 'integer', 'exists:coupons,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id', 'distinct'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
