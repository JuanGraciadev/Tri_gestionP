<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'codigo_lote' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'codigo_lote.required' => 'El código del lote es obligatorio.',
            'codigo_lote.max'      => 'El código no puede exceder 100 caracteres.',
        ];
    }
}
