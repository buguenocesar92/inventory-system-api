<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PosDevice;
use App\Models\Location;

class PosDeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todas las ubicaciones disponibles
        $locations = Location::all();

        if ($locations->isEmpty()) {
            $this->command->warn('No locations found. Run LocationSeeder first.');
            return;
        }

        // Crear POS Devices asociados a cada location
        foreach ($locations as $location) {
            PosDevice::create([
                'name' => "POS Terminal " . strtoupper(substr($location->name, 0, 3)) . rand(1, 99),
                'location_id' => $location->id,
                'identifier' => strtoupper(uniqid('POS_')),
                'status' => 'active',
            ]);
        }

        $this->command->info('POS Devices seeded successfully.');
    }
}
