<?php

namespace App\Services;

use App\Repositories\SaleRepository;
use App\Repositories\ProductStockRepository;
use App\Repositories\PosDeviceRepository;
use App\Repositories\CashRegisterRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\InsufficientStockException;

class SaleService
{
    private SaleRepository $saleRepository;
    private ProductStockRepository $productStockRepo;
    private PosDeviceRepository $posDeviceRepo;
    private CashRegisterRepository $cashRegisterRepo;
    private ProductRepository $productRepository;

    public function __construct(
        SaleRepository $saleRepository,
        ProductStockRepository $productStockRepo,
        PosDeviceRepository $posDeviceRepo,
        CashRegisterRepository $cashRegisterRepo,
        ProductRepository $productRepository
    ) {
        $this->saleRepository = $saleRepository;
        $this->productStockRepo = $productStockRepo;
        $this->posDeviceRepo = $posDeviceRepo;
        $this->cashRegisterRepo = $cashRegisterRepo;
        $this->productRepository = $productRepository;
    }

    public function create(array $data): array
    {
        $sales = [];
        $user = Auth::user();
        $locationId = $user->location_id;
        $warehouseId = $data['warehouse_id'];
        // 🔹 Obtener la caja activa del usuario en ese POS
        $cashRegister = $this->cashRegisterRepo->findOpenByUserAndPos($user->id);

        if (!$cashRegister) {
            throw new \Exception('No tienes una caja abierta en este POS.');
        }

        // 🔹 Validar que la bodega pertenece al local del usuario
/*         if (!$this->productStockRepo->validateWarehouseLocation($warehouseId)) {
            throw new \Exception('La bodega seleccionada no pertenece a tu local.');
        } */

        // 🔹 Validar que el POS pertenece al local del usuario
        if (!$this->posDeviceRepo->existsInLocation($locationId)) {
            throw new \Exception('El POS seleccionado no pertenece a tu local.');
        }

        foreach ($data['items'] as $item) {
            // 🔹 Obtener producto y validar existencia
            $product = $this->productRepository->find($item['product_id']);
            if (!$product) {
                throw new \Exception("El producto ID {$item['product_id']} no existe.");
            }

            // 🔹 Obtener el stock del producto en la bodega
            $stock = $this->productStockRepo->getStock($item['product_id'], $warehouseId);
            if (!$stock || $stock->quantity < $item['quantity']) {
                throw new InsufficientStockException(
                    "Stock insuficiente para el producto '{$product->name}' (ID: {$item['product_id']}) en la bodega {$warehouseId}."
                );
            }

            // 🔹 Obtener el precio unitario desde la base de datos
            $unitPrice = $product->unit_price;

            // 🔹 Registrar la venta en la base de datos
            $sales[] = $this->saleRepository->create([
                'product_id'      => $item['product_id'],
                'user_id'         => $user->id,
                'warehouse_id'    => $warehouseId,
                'quantity'        => $item['quantity'],
                'unit_price'      => $unitPrice, // ✅ Se obtiene desde la base de datos
                'total_price'     => $unitPrice * $item['quantity'],
                'location_id'     => $locationId,
                'cash_register_id' => $cashRegister->id, // ✅ Se asigna la caja activa
            ]);

            // 🔹 Actualizar stock en la bodega
            $this->productStockRepo->updateStock(
                $item['product_id'],
                $warehouseId,
                $stock->quantity - $item['quantity']
            );
        }

        return $sales;
    }
}
