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
            'items' => 'required|array|min:1', // items debe ser un array
            'items.*.product_id' => 'required|integer|exists:products,id', // Cada item debe tener un product_id válido
            'items.*.quantity' => 'required|integer|min:1', // Cada item debe tener una cantidad mínima de 1
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
        ];
    }

}
