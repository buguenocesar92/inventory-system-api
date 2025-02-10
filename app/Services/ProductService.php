<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Models\Product;

class ProductService
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAll(
        int $page,
        int $itemsPerPage,
        array $sortBy,
        string $search,
        ?int $locationId,
        ?int $warehouseId
    ) {
        return $this->productRepository->getAll($page, $itemsPerPage, $sortBy, $search, $locationId, $warehouseId);
    }


    public function find(Product $product): Product
    {
        return $this->productRepository->find($product);
    }

    public function create(array $data): Product
    {
        return $this->productRepository->create($data);
    }

    public function update(Product $product, array $data): Product
    {
        return $this->productRepository->update($product, $data);
    }

    public function delete(Product $product): void
    {
        $this->productRepository->delete($product);
    }

    public function findByBarcode(string $barcode): Product
    {
        return $this->productRepository->findByBarcode($barcode);
    }
}
