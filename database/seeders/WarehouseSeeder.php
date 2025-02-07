<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Location;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = Location::all(); // Obtener todos los locales

        if ($locations->isEmpty()) {
            $this->command->error('No hay locales disponibles. Primero ejecuta el seeder de Locations.');
            return;
        }

        foreach ($locations as $location) {
            Warehouse::create([
                'name' => 'Bodega Principal ' . $location->name,
                'location_id' => $location->id,
            ]);

            Warehouse::create([
                'name' => 'Bodega Secundaria ' . $location->name,
                'location_id' => $location->id,
            ]);
        }

        $this->command->info('Se han creado bodegas para cada local.');
    }
}
