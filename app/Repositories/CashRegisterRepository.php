<?php
// app/Repositories/CashRegisterRepository.php
namespace App\Repositories;

use App\Models\CashRegister;

class CashRegisterRepository
{
    /**
     * Crear un nuevo registro de caja.
     */
    public function create(array $data): CashRegister
    {
        return CashRegister::create($data);
    }

    /**
     * Buscar una caja abierta por usuario.
     */
    public function findOpenByUser(int $userId): ?CashRegister
    {
        return CashRegister::where('opened_by', $userId)
            ->whereNull('closed_at')
            ->first();
    }

    /**
     * Buscar la última caja abierta (independiente del usuario).
     */
    public function findLastOpen(): ?CashRegister
    {
        return CashRegister::whereNull('closed_at')
            ->latest('opened_at')
            ->first();
    }

    /**
     * Buscar caja por ID.
     */
    public function findById(int $id): CashRegister
    {
        return CashRegister::findOrFail($id);
    }

    /**
     * Actualizar un registro de caja.
     */
    public function update(CashRegister $cashRegister, array $data): CashRegister
    {
        $cashRegister->update($data);
        return $cashRegister;
    }
}
