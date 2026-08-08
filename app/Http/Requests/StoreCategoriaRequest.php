<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoriaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string'],
            'img_file' => ['nullable', 'image', 'mimes:jpeg,png,webp,gif,jpg', 'max:5120'],
            'imagen' => ['nullable', 'string'],
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
            'img_file.image' => 'La imagen no es válida.',
            'img_file.mimes' => 'La imagen debe ser de tipo: JPG, PNG, WEBP o GIF.',
            'img_file.max' => 'La imagen no debe superar los 5MB.',
        ];
    }
}
