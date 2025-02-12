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
            'quantity'         => $data['quantity'],
            'unit_price'       => $data['unit_price'],
            'total_price'      => $data['total_price'],
            'cash_register_id' => $data['cash_register_id'],
            'location_id'      => $data['location_id'],
            'warehouse_id'     => $data['warehouse_id'],
        ]);
    }

        /**
     * Obtener total de ventas por caja registradora.
     */
    public function getTotalSalesByCashRegister(int $cashRegisterId): float
    {
        return Sale::where('cash_register_id', $cashRegisterId)->sum('total_price');
    }

    /**
     * Contar la cantidad de ventas registradas en una caja.
     */
    public function countSalesByCashRegister(int $cashRegisterId): int
    {
        return Sale::where('cash_register_id', $cashRegisterId)->count();
    }
}
