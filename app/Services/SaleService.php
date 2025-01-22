<?php

namespace App\Services;

use App\Repositories\SaleRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CashRegisterRepository;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\InsufficientStockException;

class SaleService
{
    private SaleRepository $saleRepository;
    private ProductRepository $productRepository;
    private CashRegisterRepository $cashRegisterRepo;

    public function __construct(
        SaleRepository $saleRepository,
        ProductRepository $productRepository,
        CashRegisterRepository $cashRegisterRepo
    ) {
        $this->saleRepository = $saleRepository;
        $this->productRepository = $productRepository;
        $this->cashRegisterRepo = $cashRegisterRepo;
    }

    /**
     * Crear ventas asociadas a la caja activa (turno abierto).
     *
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function create(array $data): array
    {
        $sales = []; // Almacena todas las ventas realizadas
        $userId = Auth::id();

        // Verificar si el usuario tiene una caja activa
        $openRegister = $this->cashRegisterRepo->findOpenByUser($userId);
        if (!$openRegister) {
            throw new \Exception('No hay una caja abierta para este usuario.');
        }

        foreach ($data['items'] as $item) {
            // Obtener el producto desde la base de datos
            $product = $this->productRepository->find($item['product_id']);

            // Verificar que haya suficiente stock
            if ($product->current_stock < $item['quantity']) {
                throw new InsufficientStockException(
                    "Insufficient stock for product '{$product->name}' (ID: {$product->id})."
                );
            }

            // Obtener el precio unitario desde la tabla de productos
            $unitPrice = $product->unit_price;

            // Calcular el precio total
            $totalPrice = $unitPrice * $item['quantity'];

            // Actualizar el stock del producto
            $this->productRepository->updateStock($product, -$item['quantity']);

            // Registrar la venta, asociándola al turno abierto
            $sales[] = $this->saleRepository->create([
                'product_id'       => $product->id,
                'user_id'          => $userId, // ID del usuario autenticado
                'cash_register_id' => $openRegister->id, // Asociar a la caja activa
                'quantity'         => $item['quantity'],
                'unit_price'       => $unitPrice,
                'total_price'      => $totalPrice,
            ]);
        }

        return $sales; // Retorna todas las ventas registradas
    }
}
