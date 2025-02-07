<?php

namespace App\Services;

use App\Repositories\CashRegisterRepository;
use App\Repositories\PosDeviceRepository;
use App\Repositories\SaleRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\CashRegister;

class CashRegisterService
{
    private CashRegisterRepository $cashRegisterRepo;
    private PosDeviceRepository $posDeviceRepo;
    private SaleRepository $saleRepo;

    public function __construct(
        CashRegisterRepository $cashRegisterRepo,
        PosDeviceRepository $posDeviceRepo,
        SaleRepository $saleRepo
    ) {
        $this->cashRegisterRepo = $cashRegisterRepo;
        $this->posDeviceRepo = $posDeviceRepo;
        $this->saleRepo = $saleRepo;
    }

    /**
     * Abrir una caja asegurando validaciones.
     */
    public function open(int $userId, int $locationId, float $openingAmount, int $posDeviceId)
    {
        // Validar que el POS pertenece al local
        if (!$this->posDeviceRepo->existsInLocation($posDeviceId, $locationId)) {
            throw new \Exception('El POS seleccionado no pertenece a tu local.');
        }

        // Validar si el usuario ya tiene una caja abierta
        if ($this->getOpenCashRegisterByUser($userId)) {
            throw new \Exception('Ya tienes una caja abierta. Debes cerrarla antes de abrir una nueva.');
        }

        // Crear nueva caja si las validaciones son exitosas
        return $this->cashRegisterRepo->create([
            'opened_by' => $userId,
            'location_id' => $locationId,
            'pos_device_id' => $posDeviceId,
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

        // Obtener el total esperado sumando las ventas de la caja
        $expected = $cashRegister->opening_amount + $this->saleRepo->getTotalSalesByCashRegister($cashRegister->id);
        $difference = $closingAmount - $expected;

        // Actualizar y cerrar caja
        $updatedCashRegister = $this->cashRegisterRepo->update($cashRegister, [
            'closed_by' => $userId,
            'closing_amount' => $closingAmount,
            'difference' => $difference,
            'closed_at' => now(),
        ]);

        // Verificar si hubo ventas registradas
        $totalSales = $this->saleRepo->countSalesByCashRegister($cashRegister->id);
        if ($totalSales === 0) {
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
     * Obtener caja abierta de un usuario.
     */
    public function getOpenCashRegisterByUser(int $userId): ?CashRegister
    {
        return $this->cashRegisterRepo->findOpenByUser($userId);
    }
}

