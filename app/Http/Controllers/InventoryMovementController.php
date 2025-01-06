<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryMovementController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'movement_type' => 'required|in:entry,exit,adjustment',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->movement_type === 'exit' && $product->current_stock < $request->quantity) {
            return response()->json(['error' => 'Insufficient stock'], 400);
        }

        $movement = InventoryMovement::create($request->all());

        $product->current_stock += ($request->movement_type === 'entry') ? $request->quantity : -$request->quantity;
        $product->save();

        return $movement;
    }
}
