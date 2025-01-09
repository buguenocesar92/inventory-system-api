<?php

namespace App\Http\Requests\Inventory;
use App\Http\Requests\ApiFormRequest;

class InventoryMovementRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        // Ajusta según tu lógica de permisos, por ejemplo:
        // return auth()->check();
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'movement_type' => 'required|in:entry,exit,adjustment',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ];
    }
}
