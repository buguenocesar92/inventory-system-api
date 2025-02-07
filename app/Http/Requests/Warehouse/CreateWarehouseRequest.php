<?php

namespace App\Http\Requests\Warehouse;

use App\Http\Requests\ApiFormRequest;

class CreateWarehouseRequest extends ApiFormRequest
{
    /**
     * Determinar si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        return true; // Cambiar a lógica personalizada si necesitas verificar permisos.
    }

    /**
     * Reglas de validación.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:warehouses,name',
            'status' => 'required|in:active,inactive',
        ];
    }

    /**
     * Mensajes de error personalizados.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la bodega es obligatorio.',
            'name.unique' => 'Ya existe una bodega con este nombre.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado debe ser "active" o "inactive".',
        ];
    }
}
