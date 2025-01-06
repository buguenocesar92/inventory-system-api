<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with('movements')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'brand' => 'nullable|string|max:100',
            'barcode' => 'nullable|string|unique:products,barcode',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'current_stock' => 'required|integer|min:0',
            'reorder_point' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        return Product::create($request->all());
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'string|max:255',
            'category' => 'string|max:100',
            'brand' => 'string|max:100',
            'barcode' => 'string|unique:products,barcode,' . $product->id,
            'description' => 'string',
            'image_url' => 'url',
            'current_stock' => 'integer|min:0',
            'reorder_point' => 'integer|min:0',
            'unit_price' => 'numeric|min:0',
        ]);

        $product->update($request->all());
        return $product;
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->noContent();
    }
}
