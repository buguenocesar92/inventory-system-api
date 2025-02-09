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
            $table->dropColumn('movement_type'); // Elimina la columna con la restricción anterior
        });

        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->enum('movement_type', ['entry', 'exit', 'adjustment', 'transfer'])->after('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->dropColumn('movement_type');
        });

        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->enum('movement_type', ['entry', 'exit', 'adjustment']);
        });
    }
};
