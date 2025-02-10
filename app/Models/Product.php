<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category_id', 'brand', 'barcode',
        'description', 'image_url', 'current_stock',
        'reorder_point', 'unit_price'
    ];

    protected $casts = [
        'unit_price' => 'float',
        'current_stock' => 'integer',
    ];

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }
}
