<?php

namespace App\Http\Requests\Tenant;

use App\Http\Requests\ApiFormRequest;

class RegisterTenantRequest extends ApiFormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        return true; // Cambiar a lógica personalizada si necesitas verificar permisos.
    }

    /**
     * Reglas de validación para la solicitud.
     */
    public function rules(): array
    {
        return [
            'tenant_id'      => 'required|string|regex:/^[a-zA-Z0-9\-]+$/|unique:tenants,id',
            'user_name'      => 'required|string|max:255',
            'user_email'     => 'required|email|unique:users,email',
            'user_password'  => 'required|string|min:8',
        ];
    }

    /**
     * Mensajes de error personalizados.
     */
    public function messages(): array
    {
        return [
            'tenant_id.required'      => 'El ID del tenant es obligatorio.',
            'tenant_id.regex'         => 'El ID del tenant solo puede contener letras, números y guiones.',
            'tenant_id.unique'        => 'El ID del tenant ya está en uso.',
            'user_name.required'      => 'El nombre del usuario administrador es obligatorio.',
            'user_email.required'     => 'El correo electrónico del usuario es obligatorio.',
            'user_email.unique'       => 'El correo electrónico ya está registrado.',
            'user_password.required'  => 'La contraseña es obligatoria.',
            'user_password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
        ];
    }
}
