<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\ApiFormRequest;

class StoreProductRequest extends ApiFormRequest
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
            'barcode'       => 'nullable|string|unique:products,barcode',
            'description'   => 'nullable|string',
            'image_url'     => 'nullable|url',
            'current_stock' => 'required|integer|min:0',
            'reorder_point' => 'required|integer|min:0',
            'unit_price'    => 'required|numeric|min:0',
        ];
    }
}
