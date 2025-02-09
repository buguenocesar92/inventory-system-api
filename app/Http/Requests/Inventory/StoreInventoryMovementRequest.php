<?php

namespace App\Http\Requests\Inventory;

use App\Http\Requests\ApiFormRequest;

class StoreInventoryMovementRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'movement_type' => 'required|in:entry,exit,transfer',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',

            // 📌 `origin_warehouse_id` solo requerido para `exit` y `transfer`
            'origin_warehouse_id' => 'nullable|required_if:movement_type,exit,transfer|integer|exists:warehouses,id',

            // 📌 `destination_warehouse_id` solo requerido para `entry` y `transfer`
            'destination_warehouse_id' => 'nullable|required_if:movement_type,entry,transfer|integer|exists:warehouses,id',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'El ID del producto es obligatorio.',
            'product_id.exists' => 'El producto seleccionado no existe.',
            'quantity.required' => 'La cantidad es obligatoria.',
            'quantity.min' => 'La cantidad debe ser al menos 1.',
            'movement_type.required' => 'El tipo de movimiento es obligatorio.',
            'movement_type.in' => 'El tipo de movimiento debe ser entry, exit, transfer o adjustment.',
            'origin_warehouse_id.exists' => 'La bodega de origen no existe.',
            'destination_warehouse_id.exists' => 'La bodega de destino no existe.',
            'description.max' => 'La descripción no debe exceder los 255 caracteres.',
        ];
    }
}
