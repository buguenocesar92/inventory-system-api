<?php
// app/Http/Requests/CashRegister/CloseCashRegisterRequest.php

namespace App\Http\Requests\CashRegister;

use Illuminate\Foundation\Http\FormRequest;

class CloseCashRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ajusta si necesitas verificar permisos
    }

    public function rules(): array
    {
        return [
            'closing_amount' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'closing_amount.required' => 'El monto de cierre es obligatorio.',
            'closing_amount.numeric'  => 'El monto de cierre debe ser un número.',
            'closing_amount.min'      => 'El monto de cierre debe ser mayor o igual a 0.',
        ];
    }
}
