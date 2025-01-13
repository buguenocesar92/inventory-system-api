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
        $validatedData = $request->validated();
        $sale = $this->saleService->create($validatedData);

        return response()->json($sale, 201); // HTTP 201: Created
    }
}
