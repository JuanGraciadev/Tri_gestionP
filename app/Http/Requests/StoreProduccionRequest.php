<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProduccionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'lote_produccion'       => ['required', 'string', 'max:100'],
            'cantidad'              => ['required', 'integer', 'min:1'],
            'id_producto'           => ['required', 'integer', 'exists:producto,id_producto'],
            'id_inventario_materia' => ['nullable', 'integer', 'exists:inventario_materia_prima,id_inventario_materia'],
            'descripcion'           => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'lote_produccion.required' => 'El código de lote es obligatorio.',
            'cantidad.required'        => 'La cantidad es obligatoria.',
            'cantidad.min'             => 'La cantidad debe ser al menos 1.',
            'id_producto.required'     => 'Debes seleccionar un producto.',
            'id_producto.exists'       => 'El producto seleccionado no existe.',
        ];
    }
}
