<?php

namespace App\Repositories;

use App\Models\Sale;

class SaleRepository
{
    public function create(array $data): Sale
    {
        return Sale::create([
            'product_id'       => $data['product_id'],
            'user_id'          => $data['user_id'],
            'cash_register_id' => $data['cash_register_id'], // Añade este campo
            'quantity'         => $data['quantity'],
            'unit_price'       => $data['unit_price'],
            'total_price'      => $data['total_price'],
        ]);
    }
}
