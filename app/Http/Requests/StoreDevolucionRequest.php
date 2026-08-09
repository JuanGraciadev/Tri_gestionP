<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDevolucionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            // Solo productos marcados como retornables
            'id_producto' => [
                'required',
                'exists:producto,id_producto',
                function ($attribute, $value, $fail) {
                    $producto = \App\Models\Producto::find($value);
                    if (!$producto || !$producto->retornable) {
                        $fail('Solo se pueden devolver productos retornables (garrafones).');
                    }
                },
            ],
            'cantidad' => ['required', 'integer', 'min:1'],
            // 'apto' determina si el garrafón vuelve a stock o está dañado
            'apto'     => ['required', 'in:1,0'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_producto.required' => 'Debes seleccionar el producto a devolver.',
            'id_producto.exists'   => 'El producto seleccionado no existe.',
            'cantidad.required'    => 'La cantidad es obligatoria.',
            'cantidad.integer'     => 'La cantidad debe ser un número entero.',
            'cantidad.min'         => 'La cantidad mínima es 1.',
            'apto.required'        => 'Debes indicar el estado del garrafón.',
            'apto.in'              => 'El estado del garrafón debe ser Apto o Dañado.',
        ];
    }
}
