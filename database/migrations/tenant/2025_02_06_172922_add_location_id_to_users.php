<?php

// database/migrations/2025_02_06_000003_add_location_id_to_sales.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
        });
    }

    public function down() {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
            $table->dropColumn('location_id');
        });
    }
};
