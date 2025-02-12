<?php

namespace App\Http\Requests\Sale;

use App\Http\Requests\ApiFormRequest;

class StoreSaleRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'warehouse_id' => 'required|integer|exists:warehouses,id', // Validar que la bodega existe
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Debe incluir al menos un producto en la venta.',
            'items.*.product_id.required' => 'El ID del producto es obligatorio.',
            'items.*.product_id.exists' => 'El producto no existe.',
            'items.*.quantity.required' => 'La cantidad es obligatoria.',
            'items.*.quantity.min' => 'La cantidad debe ser al menos 1.',
            'warehouse_id.required' => 'La bodega es obligatoria.',
            'warehouse_id.exists' => 'La bodega no existe.',
        ];
    }
}

