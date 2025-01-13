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
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|numeric|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'The product ID is required.',
            'product_id.exists'   => 'The selected product does not exist.',
            'quantity.required'   => 'The quantity is required.',
            'quantity.numeric'    => 'The quantity must be a number.',
            'quantity.min'        => 'The quantity must be at least 1.',
        ];
    }
}
