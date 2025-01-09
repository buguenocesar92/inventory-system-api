<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\ApiFormRequest;

class UpdateProductRequest extends ApiFormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        // Cambiar a false si necesitas lógica personalizada para la autorización
        return true;
    }

    /**
     * Reglas de validación para la solicitud.
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'category' => 'sometimes|string|max:100',
            'brand' => 'sometimes|string|max:100',
            'barcode' => 'sometimes|string|unique:products,barcode,' . $this->product->id,
            'description' => 'sometimes|string',
            'image_url' => 'sometimes|url',
            'current_stock' => 'sometimes|integer|min:0',
            'reorder_point' => 'sometimes|integer|min:0',
            'unit_price' => 'sometimes|numeric|min:0',
        ];
    }

    /**
     * Mensajes de error personalizados.
     */
    public function messages(): array
    {
        return [
            'barcode.unique' => 'El código de barras ya está en uso.',
            'current_stock.min' => 'El stock actual no puede ser negativo.',
            'unit_price.min' => 'El precio unitario debe ser al menos 0.',
        ];
    }
}
