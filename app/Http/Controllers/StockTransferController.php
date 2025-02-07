<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ProductStockRepository;
use App\Models\ProductStock;
use Illuminate\Http\JsonResponse;

class StockTransferController extends Controller
{
    private ProductStockRepository $productStockRepo;

    public function __construct(ProductStockRepository $productStockRepo)
    {
        $this->productStockRepo = $productStockRepo;
    }

    public function transferStock(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Verificar stock en la bodega de origen
        $fromStock = $this->productStockRepo->getStock($validated['product_id'], $validated['from_warehouse_id']);

        if (!$fromStock || $fromStock->quantity < $validated['quantity']) {
            return response()->json(['error' => 'Stock insuficiente en la bodega de origen.'], 422);
        }

        // Restar stock de la bodega de origen
        $this->productStockRepo->updateStock($validated['product_id'], $validated['from_warehouse_id'], $fromStock->quantity - $validated['quantity']);

        // Sumar stock en la bodega de destino
        $toStock = $this->productStockRepo->getStock($validated['product_id'], $validated['to_warehouse_id']);
        $newQuantity = $toStock ? $toStock->quantity + $validated['quantity'] : $validated['quantity'];
        $this->productStockRepo->updateStock($validated['product_id'], $validated['to_warehouse_id'], $newQuantity);

        return response()->json(['message' => 'Transferencia de stock realizada con éxito.']);
    }
}
