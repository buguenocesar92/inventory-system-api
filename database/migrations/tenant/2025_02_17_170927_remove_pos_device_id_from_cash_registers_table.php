<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePosDeviceIdFromCashRegistersTable extends Migration
{
    /**
     * Ejecuta las migraciones: elimina la llave foránea y la columna.
     */
    public function up(): void
    {
        Schema::table('cash_registers', function (Blueprint $table) {
            // Elimina la columna.
            $table->dropColumn('pos_device_id');
        });
    }

    /**
     * Revierte las migraciones: vuelve a crear la columna y la llave foránea.
     */
    public function down(): void
    {
        Schema::table('cash_registers', function (Blueprint $table) {
            // Restaura la llave foránea.
            $table->foreign('pos_device_id')
                  ->references('id')
                  ->on('pos_devices')
                  ->onDelete('cascade');
        });
    }
}
