<?php

// app/Models/Location.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'phone', 'status'];

    /**
     * Relación: un local tiene muchos usuarios asignados.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relación: un local tiene muchos puntos de venta (POS).
     */
    public function posDevices()
    {
        return $this->hasMany(POSDevice::class);
    }

    /**
     * Relación: un local tiene muchas cajas registradoras.
     */
    public function cashRegisters()
    {
        return $this->hasMany(CashRegister::class);
    }

    /**
     * Relación: un local tiene muchas ventas.
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }
}
