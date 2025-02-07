<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function getAll(int $page, int $itemsPerPage, array $sortBy, string $search): array
    {
        $query = Product::with('category'); // Incluir la relación de categoría

        // Aplicar búsqueda
        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhereHas('category', function ($q) use ($search) { // Búsqueda en la categoría
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhere('brand', 'like', '%' . $search . '%');
        }

        // Aplicar ordenamiento
        if (!empty($sortBy)) {
            foreach ($sortBy as $sort) {
                $query->orderBy($sort['key'], $sort['order'] ?? 'asc');
            }
        }

        // Paginación
        $total = $query->count();
        $items = $query->skip(($page - 1) * $itemsPerPage)
            ->take($itemsPerPage)
            ->get();

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
