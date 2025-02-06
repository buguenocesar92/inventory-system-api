<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        Location::create([
            'name' => 'Local Central',
            'address' => 'Calle Falsa 123',
            'city' => 'Santiago',
            'country' => 'Chile',
        ]);

        Location::create([
            'name' => 'Sucursal Norte',
            'address' => 'Avenida Siempre Viva 742',
            'city' => 'Santiago',
            'country' => 'Chile',
        ]);

        Location::create([
            'name' => 'Sucursal Sur',
            'address' => 'Carrera 50 #15-20',
            'city' => 'Bogotá',
            'country' => 'Colombia',
        ]);
    }
}
