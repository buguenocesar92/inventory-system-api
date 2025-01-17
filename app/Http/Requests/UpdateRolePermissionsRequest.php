<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRolePermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cambia según tu lógica de autorización
    }

    public function rules(): array
    {
        return [
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,id',
        ];
    }

    public function messages(): array
    {
        return [
            'permissions.required' => 'The permissions field is required.',
            'permissions.array' => 'The permissions field must be an array.',
            'permissions.min' => 'You must provide at least one permission.',
            'permissions.*.exists' => 'One or more permissions do not exist.',
        ];
    }
}
