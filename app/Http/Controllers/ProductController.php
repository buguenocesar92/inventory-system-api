<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Services\ProductService;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Listar todos los productos.
     */
    public function index(): JsonResponse
    {
        $products = $this->productService->getAll();
        return response()->json($products);
    }

    /**
     * Mostrar un producto específico.
     */
    public function show(Product $product): JsonResponse
    {
        $product = $this->productService->find($product);
        return response()->json($product);
    }

    /**
     * Crear un nuevo producto.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $product = $this->productService->create($validatedData);
        return response()->json($product, 201); // HTTP 201: Created
    }

    /**
     * Actualizar un producto existente.
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $validatedData = $request->validated();
        $product = $this->productService->update($product, $validatedData);
        return response()->json($product);
    }

    /**
     * Eliminar un producto.
     */
    public function destroy(Product $product): JsonResponse
    {
        $this->productService->delete($product);
        return response()->json(['message' => 'Product deleted successfully.'], 200); // HTTP 200: OK
    }
}
