<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('origin_warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');
            $table->foreignId('destination_warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');
            $table->foreignId('origin_location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->foreignId('destination_location_id')->nullable()->constrained('locations')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropForeign(['origin_warehouse_id']);
            $table->dropColumn('origin_warehouse_id');
            $table->dropForeign(['destination_warehouse_id']);
            $table->dropColumn('destination_warehouse_id');
            $table->dropForeign(['origin_location_id']);
            $table->dropColumn('origin_location_id');
            $table->dropForeign(['destination_location_id']);
            $table->dropColumn('destination_location_id');
        });
    }
};
