<?php
// app/Http/Requests/CashRegister/OpenCashRegisterRequest.php

namespace App\Http\Requests\CashRegister;

use App\Http\Requests\ApiFormRequest;

class OpenCashRegisterRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true; // Ajusta si necesitas verificar permisos
    }

    public function rules(): array
    {
        return [
            'opening_amount' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'opening_amount.required' => 'El monto de apertura es obligatorio.',
            'opening_amount.numeric'  => 'El monto de apertura debe ser un número.',
            'opening_amount.min'      => 'El monto de apertura debe ser mayor o igual a 0.',
        ];
    }
}
