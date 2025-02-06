<?php
namespace App\Services;

use App\Repositories\CashRegisterRepository;
use Illuminate\Support\Facades\Auth;

class CashRegisterService
{
    private CashRegisterRepository $cashRegisterRepo;

    public function __construct(CashRegisterRepository $cashRegisterRepo)
    {
        $this->cashRegisterRepo = $cashRegisterRepo;
    }

    /**
     * Abrir caja.
     */
    public function open(float $openingAmount)
    {
        $userId = Auth::id();
        $locationId = Auth::user()->location_id;

        $existingOpen = $this->cashRegisterRepo->findOpenByUser($userId);
        if ($existingOpen) {
            throw new \Exception('Ya existe una caja abierta para este usuario.');
        }

        return $this->cashRegisterRepo->create([
            'opened_by' => $userId,
            'opening_amount' => $openingAmount,
            'opened_at' => now(),
            'location_id' => $locationId,
        ]);
    }

    /**
     * Cerrar caja.
     */
    public function closeByUser(int $userId, float $closingAmount): array
    {
        $cashRegister = $this->cashRegisterRepo->findOpenByUser($userId);

        if (!$cashRegister) {
            throw new \Exception('No hay una caja abierta para este usuario.');
        }

        $expected = $cashRegister->opening_amount + $cashRegister->sales->sum('total_price');
        $difference = $closingAmount - $expected;

        $updatedCashRegister = $this->cashRegisterRepo->update($cashRegister, [
            'closed_by' => $userId,
            'closing_amount' => $closingAmount,
            'difference' => $difference,
            'closed_at' => now(),
        ]);

        // Validación: Sin ventas registradas
        if ($cashRegister->sales->isEmpty()) {
            return [
                'message' => 'Caja cerrada con éxito, pero no se registraron ventas.',
                'cash_register' => $updatedCashRegister,
            ];
        }

        return [
            'message' => 'Caja cerrada con éxito.',
            'cash_register' => $updatedCashRegister,
        ];
    }

    /**
     * Consultar estado de la caja.
     */
    public function getStatus(): bool
    {
        $cashRegister = $this->cashRegisterRepo->findLastOpen();

        return $cashRegister && !$cashRegister->closed_at;
    }
}
