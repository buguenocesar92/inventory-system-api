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

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class);
    }
}
