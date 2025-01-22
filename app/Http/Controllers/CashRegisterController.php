<?php

namespace App\Http\Controllers;

use App\Http\Requests\CashRegister\OpenCashRegisterRequest;
use App\Http\Requests\CashRegister\CloseCashRegisterRequest;
use App\Services\CashRegisterService;
use Illuminate\Http\JsonResponse;

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
            $cashRegister = $this->cashRegisterService->open($request->validated()['opening_amount']);
            return response()->json([
                'message' => 'Caja abierta con éxito.',
                'cash_register' => $cashRegister,
            ], 201); // HTTP 201: Created
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * Cerrar una caja.
     */
    public function close(CloseCashRegisterRequest $request, int $id): JsonResponse
    {
        try {
            $cashRegister = $this->cashRegisterService->close($id, $request->validated()['closing_amount']);
            return response()->json([
                'message' => 'Caja cerrada con éxito.',
                'cash_register' => $cashRegister,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * Consultar el estado actual de la caja.
     */
    public function status(): JsonResponse
    {
        try {
            $status = $this->cashRegisterService->getStatus();
            return response()->json([
                'is_open' => $status,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
