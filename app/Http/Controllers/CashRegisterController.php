<?php

namespace App\Http\Controllers;

use App\Http\Requests\CashRegister\OpenCashRegisterRequest;
use App\Http\Requests\CashRegister\CloseCashRegisterRequest;
use App\Services\CashRegisterService;
use App\Models\PosDevice;
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

            // Validar que el POS pertenece al local del usuario
            $posDevice = PosDevice::where('id', $request->validated()['pos_device_id'])
                ->where('location_id', $locationId)
                ->first();

            if (!$posDevice) {
                return response()->json(['error' => 'El POS seleccionado no pertenece a tu local.'], 403);
            }

            // Validar si el usuario ya tiene una caja abierta
            $existingCashRegister = $this->cashRegisterService->getOpenCashRegisterByUser($user->id);
            if ($existingCashRegister) {
                return response()->json(['error' => 'Ya tienes una caja abierta. Debes cerrarla antes de abrir una nueva.'], 422);
            }

            // Abrir nueva caja si no tiene otra abierta
            $cashRegister = $this->cashRegisterService->open(
                $request->validated()['opening_amount'],
                $request->validated()['pos_device_id']
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
