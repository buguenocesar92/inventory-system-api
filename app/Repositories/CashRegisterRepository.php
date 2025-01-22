<?php
// app/Repositories/CashRegisterRepository.php
namespace App\Repositories;

use App\Models\CashRegister;

class CashRegisterRepository
{
    public function create(array $data): CashRegister
    {
        return CashRegister::create($data);
    }

    public function findOpenByUser(int $userId): ?CashRegister
    {
        return CashRegister::where('opened_by', $userId)
            ->whereNull('closed_at')
            ->first();
    }

    public function findById(int $id): CashRegister
    {
        return CashRegister::findOrFail($id);
    }

    public function update(CashRegister $cashRegister, array $data): CashRegister
    {
        $cashRegister->update($data);
        return $cashRegister;
    }
}
