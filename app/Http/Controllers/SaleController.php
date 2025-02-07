<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sale\StoreSaleRequest;
use App\Services\SaleService;
use Illuminate\Http\JsonResponse;

class SaleController extends Controller
{
    private SaleService $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    /**
     * Registrar una nueva venta.
     */
    public function store(StoreSaleRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $sales = $this->saleService->create($validatedData);

            return response()->json([
                'message' => 'Sales registered successfully.',
                'sales' => $sales,
            ], 201); // HTTP 201: Created

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }


}
