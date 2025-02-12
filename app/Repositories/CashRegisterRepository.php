<?php
// app/Repositories/CashRegisterRepository.php
namespace App\Repositories;

use App\Models\CashRegister;

class CashRegisterRepository
{
    /**
     * Crear una nueva caja registradora.
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
     * Buscar caja por ID.
     */
    public function findById(int $id): CashRegister
    {
        return CashRegister::findOrFail($id);
    }

    /**
     * Actualizar caja.
     */
    public function update(CashRegister $cashRegister, array $data): CashRegister
    {
        $cashRegister->update($data);
        return $cashRegister;
    }

    /**
     * Buscar la caja activa de un usuario en un POS específico.
     */
    public function findOpenByUserAndPos(int $userId): ?CashRegister
    {
        return CashRegister::where('opened_by', $userId)
            ->whereNull('closed_at')
            ->first();
    }
}
