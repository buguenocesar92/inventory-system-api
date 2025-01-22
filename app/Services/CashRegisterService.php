<?php
// app/Services/CashRegisterService.php
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

    public function open(float $openingAmount)
    {
        $userId = Auth::id();

        $existingOpen = $this->cashRegisterRepo->findOpenByUser($userId);
        if ($existingOpen) {
            throw new \Exception('Ya existe una caja abierta para este usuario.');
        }

        return $this->cashRegisterRepo->create([
            'opened_by' => $userId,
            'opening_amount' => $openingAmount,
            'opened_at' => now(),
        ]);
    }

    public function close(int $cashRegisterId, float $closingAmount)
    {
        $cashRegister = $this->cashRegisterRepo->findById($cashRegisterId);
        $expected = $cashRegister->opening_amount + $cashRegister->sales->sum('total_price');
        $difference = $closingAmount - $expected;

        return $this->cashRegisterRepo->update($cashRegister, [
            'closed_by' => Auth::id(),
            'closing_amount' => $closingAmount,
            'difference' => $difference,
            'closed_at' => now(),
        ]);
    }
}
