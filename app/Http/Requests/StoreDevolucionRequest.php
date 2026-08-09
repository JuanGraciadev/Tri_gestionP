<?php

namespace App\Http\Requests;

use App\Models\Producto;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreDevolucionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && in_array((int) Auth::user()->id_rol, [1, 2]);
    }

    public function rules(): array
    {
        return [
            'id_usuario'    => ['required', 'integer', 'exists:usuarios,id_usuario'],
            'id_producto'   => [
                'required',
                'integer',
                'exists:producto,id_producto',
                function ($attribute, $value, $fail) {
                    $producto = Producto::find($value);
                    if (!$producto || !$producto->retornable) {
                        $fail('Únicamente los garrafones/envases retornables pueden procesarse en devoluciones.');
                    }
                },
            ],
            'cantidad'      => ['required', 'integer', 'min:1'],
            'bodega'        => ['required', 'string', 'max:100'],
            'estado_envase' => ['nullable', 'string', 'in:bueno,danado'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_usuario.required'  => 'Debes seleccionar un cliente.',
            'id_usuario.exists'    => 'El cliente seleccionado no existe.',
            'id_producto.required' => 'Debes seleccionar un envase retornable.',
            'cantidad.required'    => 'La cantidad es obligatoria.',
            'cantidad.min'         => 'La cantidad debe ser al menos 1.',
        ];
    }
}
