<?php

namespace App\Services;

use App\Repositories\SaleRepository;
use App\Repositories\ProductStockRepository; // Ensure this class exists in the specified namespace
use App\Repositories\PosDeviceRepository;
use App\Repositories\CashRegisterRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\InsufficientStockException;

class SaleService
{
    private SaleRepository $saleRepository;
    private ProductStockRepository $productStockRepo;
    private CashRegisterRepository $cashRegisterRepo;
    private ProductRepository $productRepository;

    public function __construct(
        SaleRepository $saleRepository,
        ProductStockRepository $productStockRepo,
        CashRegisterRepository $cashRegisterRepo,
        ProductRepository $productRepository
    ) {
        $this->saleRepository = $saleRepository;
        $this->productStockRepo = $productStockRepo;
        $this->cashRegisterRepo = $cashRegisterRepo;
        $this->productRepository = $productRepository;
    }

    public function create(array $data): array
    {
        $sales = [];
        $user = Auth::user();
        $locationId = $user->location_id;

        // 🔹 Obtener la bodega de ventas del local
        $warehouseId = $this->productStockRepo->getSalesWarehouse($locationId);

        if (!$warehouseId) {
            throw new \Exception('No hay una bodega asignada para ventas en este local.');
        }

        // 🔹 Obtener la caja activa del usuario en ese POS
        $cashRegister = $this->cashRegisterRepo->findOpenByUserAndPos($user->id);

        if (!$cashRegister) {
            throw new \Exception('No tienes una caja abierta en este POS.');
        }

        foreach ($data['items'] as $item) {
            // 🔹 Obtener producto y validar existencia
            $product = $this->productRepository->find($item['product_id']);
            if (!$product) {
                throw new \Exception("El producto ID {$item['product_id']} no existe.");
            }

            // 🔹 Obtener el stock del producto en la bodega de ventas
            $stock = $this->productStockRepo->getStock($item['product_id'], $warehouseId);
            if (!$stock || $stock->quantity < $item['quantity']) {
                throw new InsufficientStockException(
                    "Stock insuficiente para el producto '{$product->name}' (ID: {$item['product_id']}) en la bodega de ventas."
                );
            }

            // 🔹 Registrar la venta en la base de datos
            $sales[] = $this->saleRepository->create([
                'product_id'       => $item['product_id'],
                'user_id'          => $user->id,
                'warehouse_id'     => $warehouseId,
                'quantity'         => $item['quantity'],
                'unit_price'       => $product->unit_price,
                'total_price'      => $product->unit_price * $item['quantity'],
                'location_id'      => $locationId,
                'cash_register_id' => $cashRegister->id,
            ]);

            // 🔹 Descontar stock
            $this->productStockRepo->decrementStock($item['product_id'], $warehouseId, $item['quantity']);
        }

        return $sales;
    }

}
