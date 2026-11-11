<?php

namespace App\Http\Requests\Ventas;

use Illuminate\Foundation\Http\FormRequest;

class CambiarEstadoVentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_venta' => ['required', 'integer', 'exists:venta,id_venta'],
            'estado'   => ['required', 'string', 'in:Pendiente,En Proceso,Entregado,Cancelado'],
        ];
    }
}
