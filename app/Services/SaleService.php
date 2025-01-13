<?php
namespace App\Services;

use App\Repositories\SaleRepository;
use App\Repositories\ProductRepository;

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
        // Obtener el producto desde la base de datos
        $product = $this->productRepository->find($data['product_id']);

        // Verificar que haya suficiente stock
        if ($product->current_stock < $data['quantity']) {
            throw new \Exception('Insufficient stock for this product.');
        }

        // Obtener el precio unitario desde la tabla de productos
        $unitPrice = $product->unit_price;

        // Calcular el precio total
        $totalPrice = $unitPrice * $data['quantity'];

        // Actualizar el stock del producto
        $this->productRepository->updateStock($product, -$data['quantity']);

        // Registrar la venta
        return $this->saleRepository->create([
            'product_id'  => $product->id,
            'quantity'    => $data['quantity'],
            'unit_price'  => $unitPrice,
            'total_price' => $totalPrice,
        ]);
    }
}
