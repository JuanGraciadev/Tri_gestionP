<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductoRequest extends FormRequest
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
            'precio' => ['required', 'numeric', 'min:0'],
            'id_categoria' => ['required', 'exists:categoria,id_categoria'],
            'retornable' => ['nullable', 'boolean'],
            'img_file' => ['nullable', 'image', 'mimes:jpeg,png,webp,gif,jpg', 'max:5120'],
            'img_actual' => ['nullable', 'string'],
        ];
    }

    /**
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un valor numérico.',
            'precio.min' => 'El precio no puede ser negativo.',
            'id_categoria.required' => 'Debes seleccionar una categoría.',
            'id_categoria.exists' => 'La categoría seleccionada no existe.',
            'img_file.image' => 'La imagen no es válida.',
            'img_file.mimes' => 'La imagen debe ser de tipo: JPG, PNG, WEBP o GIF.',
            'img_file.max' => 'La imagen no debe superar los 5MB.',
        ];
    }
}
