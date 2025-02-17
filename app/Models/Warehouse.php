<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = ['location_id', 'name', 'type', 'is_sales_warehouse'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }
}
