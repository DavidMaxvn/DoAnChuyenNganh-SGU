<?php

namespace App\Http\Requests\Vibe\Stage04;

use Illuminate\Foundation\Http\FormRequest;

class SocialCallbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'provider' => ['required', 'string', 'max:50'],
            'provider_user_id' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'name' => ['nullable', 'string', 'max:255'],
        ];
    }
}
