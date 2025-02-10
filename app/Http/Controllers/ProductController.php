<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Services\ProductService;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
    public function index(Request $request): JsonResponse
    {
        $products = $this->productService->getAll(
            $request->query('page', 1), // Página actual
            $request->query('itemsPerPage', 10), // Número de elementos por página
            $request->query('sortBy', []), // Ordenamiento
            $request->query('search', ''), // Búsqueda
            $request->query('locationId', null), // Filtrar por local
            $request->query('warehouseId', null) // Filtrar por bodega
        );

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

     /**
     * Buscar un producto por código de barras.
     */
    public function showByBarcode(string $barcode): JsonResponse
    {
        $product = $this->productService->findByBarcode($barcode);
        return response()->json($product);
    }
}
