<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoteDetalleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'id_lote'    => ['required', 'exists:lote,id_lote'],
            'unidades'   => ['required', 'integer', 'min:1'],
            'tipo_envase'=> ['required', 'string', 'max:50'],
            'capacidad'  => ['required', 'string', 'max:50'],
            'proveedor'  => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_lote.required'     => 'El lote es requerido.',
            'id_lote.exists'       => 'El lote seleccionado no existe.',
            'unidades.required'    => 'Las unidades son obligatorias.',
            'unidades.integer'     => 'Las unidades deben ser un número entero.',
            'unidades.min'         => 'Las unidades deben ser al menos 1.',
            'tipo_envase.required' => 'El tipo de envase es obligatorio.',
            'tipo_envase.max'      => 'El tipo de envase no puede exceder 50 caracteres.',
            'capacidad.required'   => 'La capacidad es obligatoria.',
            'capacidad.max'        => 'La capacidad no puede exceder 50 caracteres.',
            'proveedor.required'   => 'El proveedor es obligatorio.',
            'proveedor.max'        => 'El proveedor no puede exceder 100 caracteres.',
        ];
    }
}
