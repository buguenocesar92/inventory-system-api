<?php
namespace App\Services;

use App\Repositories\SaleRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\InsufficientStockException;


class SaleService
{
    private SaleRepository $saleRepository;
    private ProductRepository $productRepository;

    public function __construct(SaleRepository $saleRepository, ProductRepository $productRepository)
    {
        $this->saleRepository = $saleRepository;
        $this->productRepository = $productRepository;
    }

    public function create(array $data)
    {
        $sales = []; // Almacena todas las ventas realizadas

        foreach ($data['items'] as $item) {
            // Obtener el producto desde la base de datos
            $product = $this->productRepository->find($item['product_id']);

            // Verificar que haya suficiente stock
            if ($product->current_stock < $item['quantity']) {
                throw new InsufficientStockException();
            }

            // Obtener el precio unitario desde la tabla de productos
            $unitPrice = $product->unit_price;

            // Calcular el precio total
            $totalPrice = $unitPrice * $item['quantity'];

            // Actualizar el stock del producto
            $this->productRepository->updateStock($product, -$item['quantity']);

            // Registrar la venta
            $sales[] = $this->saleRepository->create([
                'product_id'  => $product->id,
                'user_id'     => Auth::id(), // ID del usuario autenticado
                'quantity'    => $item['quantity'],
                'unit_price'  => $unitPrice,
                'total_price' => $totalPrice,
            ]);
        }

        return $sales; // Retorna todas las ventas registradas
    }

}

