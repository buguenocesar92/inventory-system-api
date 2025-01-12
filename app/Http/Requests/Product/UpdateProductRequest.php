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
            'category'      => 'required|string|max:100',
            'brand'         => 'nullable|string|max:100',
            'barcode'       => 'nullable|string|unique:products,barcode,' . $this->product->id . '|regex:/^\d+$/', // Solo números
            'description'   => 'nullable|string',
            'image_url'     => 'nullable|url',
            'unit_price'    => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'The product name is required.',
            'category.required'    => 'The category is required.',
            'barcode.regex'        => 'The barcode must contain only numbers.',
            'barcode.unique'       => 'The barcode has already been taken.',
            'unit_price.required'  => 'The unit price is required.',
            'unit_price.numeric'   => 'The unit price must be a number.',
            'unit_price.min'       => 'The unit price must be at least 0.',
        ];
    }
}
