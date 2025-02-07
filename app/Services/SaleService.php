<?php

namespace App\Services;

use App\Repositories\SaleRepository;
use App\Repositories\ProductStockRepository;
use App\Repositories\PosDeviceRepository;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\InsufficientStockException;

class SaleService
{
    private SaleRepository $saleRepository;
    private ProductStockRepository $productStockRepo;
    private PosDeviceRepository $posDeviceRepo;

    public function __construct(
        SaleRepository $saleRepository,
        ProductStockRepository $productStockRepo,
        PosDeviceRepository $posDeviceRepo
    ) {
        $this->saleRepository = $saleRepository;
        $this->productStockRepo = $productStockRepo;
        $this->posDeviceRepo = $posDeviceRepo;
    }

    public function create(array $data): array
    {
        $sales = [];
        $user = Auth::user();
        $locationId = $user->location_id;
        $warehouseId = $data['warehouse_id'];
        $posDeviceId = $data['pos_device_id'];

        // 🔹 Validar que la bodega pertenece al local del usuario
        if (!$this->productStockRepo->validateWarehouseLocation($warehouseId, $locationId)) {
            throw new \Exception('La bodega seleccionada no pertenece a tu local.');
        }

        // 🔹 Validar que el POS pertenece al local del usuario (Usando `existsInLocation()`)
        if (!$this->posDeviceRepo->existsInLocation($posDeviceId, $locationId)) {
            throw new \Exception('El POS seleccionado no pertenece a tu local.');
        }

        foreach ($data['items'] as $item) {
            // Obtener stock del producto en la bodega
            $stock = $this->productStockRepo->getStock($item['product_id'], $warehouseId);

            if (!$stock || $stock->quantity < $item['quantity']) {
                throw new InsufficientStockException(
                    "Stock insuficiente para el producto ID {$item['product_id']} en la bodega {$warehouseId}."
                );
            }

            // Registrar venta
            $sales[] = $this->saleRepository->create([
                'product_id'  => $item['product_id'],
                'user_id'     => $user->id,
                'warehouse_id' => $warehouseId,
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price'],
                'total_price' => $item['unit_price'] * $item['quantity'],
                'location_id' => $locationId,
                'pos_device_id' => $posDeviceId,
            ]);

            // Actualizar stock
            $this->productStockRepo->updateStock(
                $item['product_id'],
                $warehouseId,
                $stock->quantity - $item['quantity']
            );
        }

        return $sales;
    }
}
