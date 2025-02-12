<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->boolean('is_sales_warehouse')->default(false)->after('name');
        });
    }

    public function down()
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropColumn('is_sales_warehouse');
        });
    }
};
