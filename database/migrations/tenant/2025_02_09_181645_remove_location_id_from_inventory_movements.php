<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->dropForeign(['location_id']); // Elimina la clave foránea si existía
            $table->dropColumn('location_id'); // Elimina la columna location_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');
        });
    }
};
