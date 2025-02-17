<?php

namespace App\Http\Requests\Warehouse;

use App\Http\Requests\ApiFormRequest;

class SetSalesWarehouseRequest extends ApiFormRequest
{
    /**
     * Determinar si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        return true; // Agrega lógica de autorización según tus necesidades.
    }

    /**
     * Reglas de validación.
     */
    public function rules(): array
    {
        return [
            'is_sales_warehouse' => 'required|boolean',
        ];
    }

    /**
     * Mensajes de error personalizados.
     */
    public function messages(): array
    {
        return [
            'is_sales_warehouse.required' => 'El estado de bodega de ventas es obligatorio.',
            'is_sales_warehouse.boolean'  => 'El estado de bodega de ventas debe ser verdadero o falso.',
        ];
    }
}
