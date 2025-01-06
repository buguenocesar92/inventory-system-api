<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with('movements')->get();
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
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

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        return Product::create($request->all());
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make(request()->all(), [
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

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $product->update($request->all());
        return $product;
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully.'], 200); // HTTP 200: OK
    }

}
