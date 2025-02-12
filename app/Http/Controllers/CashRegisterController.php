<?php

namespace App\Http\Controllers;

use App\Http\Requests\CashRegister\OpenCashRegisterRequest;
use App\Http\Requests\CashRegister\CloseCashRegisterRequest;
use App\Services\CashRegisterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CashRegisterController extends Controller
{
    private CashRegisterService $cashRegisterService;

    public function __construct(CashRegisterService $cashRegisterService)
    {
        $this->cashRegisterService = $cashRegisterService;
    }

    /**
     * Abrir una caja.
     */
    public function open(OpenCashRegisterRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $locationId = $user->location_id;

            // Abrir caja con validaciones
            $cashRegister = $this->cashRegisterService->open(
                $user->id,
                $locationId,
                $request->validated()['opening_amount'],
            );


            return response()->json([
                'message' => 'Caja abierta con éxito.',
                'cash_register' => $cashRegister,
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * Cerrar una caja.
     */
    public function close(CloseCashRegisterRequest $request): JsonResponse
    {
        try {
            $userId = Auth::id();
            $closingAmount = $request->validated()['closing_amount'];

            $result = $this->cashRegisterService->closeByUser($userId, $closingAmount);

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
