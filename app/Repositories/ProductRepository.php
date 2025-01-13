<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{

    public function getAll(int $page, int $itemsPerPage, array $sortBy, string $search): array
    {
        $query = Product::query();

        // Aplicar búsqueda
        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('category', 'like', '%' . $search . '%')
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
            return $product; // Ya es una instancia de Product
        }

        // Buscar el producto por su ID
        return Product::findOrFail($product); // Lanza excepción si no se encuentra
    }


    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product;
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

}
