<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->string('brand')->nullable();
            $table->string('barcode')->unique()->nullable();
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->integer('current_stock')->default(0);
            $table->integer('reorder_point')->default(5);
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}

