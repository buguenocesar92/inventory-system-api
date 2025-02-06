<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('pos_devices', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedBigInteger('location_id'); // Relación con el local
            $table->string('identifier')->unique(); // ID único del POS (puede ser un número de serie)
            $table->enum('status', ['active', 'inactive'])->default('active'); // Estado del POS
            $table->timestamps();

            // Clave foránea
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('pos_devices');
    }
};
