<?php

namespace App\Http\Requests\Ventas;

use Illuminate\Foundation\Http\FormRequest;

class CrearPosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_cliente'     => ['required', 'integer', 'exists:usuarios,id_usuario'],
            'pos_producto'   => ['required', 'array', 'min:1'],
            'pos_producto.*' => ['integer'],
            'pos_cantidad'   => ['required', 'array'],
            'pos_cantidad.*' => ['integer', 'min:1'],
            'metodo_pago'    => ['required', 'string', 'in:Efectivo,Transferencia,Tarjeta'],
            'notas'          => ['nullable', 'string', 'max:500'],
        ];
    }
}
