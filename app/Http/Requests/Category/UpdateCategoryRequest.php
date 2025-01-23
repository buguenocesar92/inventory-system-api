<?php

namespace App\Http\Requests\Category;

use App\Http\Requests\ApiFormRequest;

class UpdateCategoryRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true; // Ajusta según permisos
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name,' . $this->route('category'),
            'description' => 'nullable|string',
        ];
    }

}
