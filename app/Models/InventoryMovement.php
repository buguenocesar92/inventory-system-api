<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'movement_type', 'quantity', 'description'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
