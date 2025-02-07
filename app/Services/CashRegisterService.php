<?php
namespace App\Services;

use App\Repositories\CashRegisterRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\CashRegister;

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
    public function open(float $openingAmount, int $posDeviceId)
    {
        $userId = Auth::id();
        $locationId = Auth::user()->location_id; // Obtenemos el local asignado

        return $this->cashRegisterRepo->create([
            'opened_by' => $userId,
            'location_id' => $locationId, // Asignamos la ubicación
            'pos_device_id' => $posDeviceId, // POS seleccionado por el usuario
            'opening_amount' => $openingAmount,
            'opened_at' => now(),
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
     * Obtener la caja abierta de un usuario.
     */
    public function getOpenCashRegisterByUser(int $userId): ?CashRegister
    {
        return $this->cashRegisterRepo->findOpenByUser($userId);
    }

}
