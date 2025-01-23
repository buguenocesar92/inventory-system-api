<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Listar todas las categorías.
     */
    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getAll();
        return response()->json($categories);
    }

    /**
     * Mostrar una categoría específica.
     */
    public function show(int $id): JsonResponse
    {
        $category = $this->categoryService->findById($id);
        return response()->json($category);
    }

    /**
     * Crear una nueva categoría.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->create($request->validated());
        return response()->json(['message' => 'Categoría creada con éxito.', 'category' => $category], 201);
    }

    /**
     * Actualizar una categoría existente.
     */
    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        $category = $this->categoryService->update($id, $request->validated());
        return response()->json(['message' => 'Categoría actualizada con éxito.', 'category' => $category]);
    }

    /**
     * Eliminar una categoría.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->categoryService->delete($id);
        return response()->json(['message' => 'Categoría eliminada con éxito.']);
    }
}
