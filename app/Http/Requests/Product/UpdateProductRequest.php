<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\ApiFormRequest;

class UpdateProductRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id', // Verifica que el ID de categoría exista
            'brand'         => 'nullable|string|max:100',
            'barcode'       => 'nullable|string|unique:products,barcode,' . $this->route('product')->id . '|regex:/^\d+$/', // Verifica unicidad excepto para el producto actual
            'description'   => 'nullable|string',
            'image_url'     => 'nullable|url',
            'unit_price'    => 'required|numeric|min:0',
            'current_stock' => 'nullable|integer|min:0',
            'reorder_point' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'The product name is required.',
            'category_id.required' => 'The category is required.',
            'category_id.exists'   => 'The selected category does not exist.',
            'barcode.regex'        => 'The barcode must contain only numbers.',
            'barcode.unique'       => 'The barcode has already been taken.',
            'unit_price.required'  => 'The unit price is required.',
            'unit_price.numeric'   => 'The unit price must be a number.',
            'unit_price.min'       => 'The unit price must be at least 0.',
            'current_stock.integer'=> 'The current stock must be an integer.',
            'current_stock.min'    => 'The current stock cannot be negative.',
            'reorder_point.integer'=> 'The reorder point must be an integer.',
            'reorder_point.min'    => 'The reorder point cannot be negative.',
        ];
    }
}
