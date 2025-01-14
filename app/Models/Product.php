<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category', 'brand', 'barcode',
        'description', 'image_url', 'current_stock',
        'reorder_point', 'unit_price'
    ];

    protected $casts = [
        'unit_price' => 'float', // Forzar unit_price como float
        'current_stock' => 'integer', // Opcional: convertir current_stock a integer
    ];


    public function movements()
    {
        return $this->hasMany(InventoryMovement::class);
    }
}
