<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductStock;

class ProductRepository
{
    public function getAll(
        int $page,
        int $itemsPerPage,
        array $sortBy,
        string $search,
        ?int $locationId,
        ?int $warehouseId
    ): array {
        $query = Product::with('category')
            ->with(['stocks' => function ($q) use ($locationId, $warehouseId) {
                // 🔹 Filtrar por local si está presente
                if ($locationId) {
                    $q->whereHas('warehouse', function ($wq) use ($locationId) {
                        $wq->where('location_id', $locationId);
                    });
                }

                // 🔹 Filtrar por bodega si está presente
                if ($warehouseId) {
                    $q->where('warehouse_id', $warehouseId);
                }
            }]);

        // 🔹 Aplicar búsqueda en nombre, marca y categoría
        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhereHas('category', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhere('brand', 'like', '%' . $search . '%');
        }

        // 🔹 Aplicar ordenamiento
        if (!empty($sortBy)) {
            foreach ($sortBy as $sort) {
                $query->orderBy($sort['key'], $sort['order'] ?? 'asc');
            }
        }

        // 🔹 Paginación
        $total = $query->count();
        $items = $query->skip(($page - 1) * $itemsPerPage)
            ->take($itemsPerPage)
            ->get();

        // 🔹 Agregar stock total en cada producto
        $items->transform(function ($product) use ($locationId, $warehouseId) {
            $stockQuery = ProductStock::where('product_id', $product->id);

            // 🔹 Si se filtra por local, sumar stock solo en bodegas de ese local
            if ($locationId) {
                $stockQuery->whereHas('warehouse', function ($q) use ($locationId) {
                    $q->where('location_id', $locationId);
                });
            }

            // 🔹 Si se filtra por bodega, sumar stock solo en esa bodega
            if ($warehouseId) {
                $stockQuery->where('warehouse_id', $warehouseId);
            }

            // 🔹 Sumar el stock filtrado
            $product->total_stock = $stockQuery->sum('quantity');

            return $product;
        });

        return [
            'items' => $items,
            'total' => $total,
        ];
    }



    public function find($product): Product
    {
        if ($product instanceof Product) {
            return $product->load('category'); // Cargar la relación de categoría
        }

        // Buscar el producto por su ID
        return Product::with('category')->findOrFail($product); // Cargar relación
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product->load('category'); // Actualizar y cargar relación
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    public function updateStock(Product $product, float $quantity): void
    {
        $product->current_stock += $quantity;
        $product->save();
    }

    public function findByBarcode(string $barcode): Product
    {
        return Product::with('category')->where('barcode', $barcode)->firstOrFail(); // Cargar relación
    }

}
