<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location_id',
        'identifier',
        'status',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function cashRegisters()
    {
        return $this->hasMany(CashRegister::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

}
