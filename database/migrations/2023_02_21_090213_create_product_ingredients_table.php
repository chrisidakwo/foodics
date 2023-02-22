<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_ingredients', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('ingredient_id');
            $table->integer('ingredient_quantity')->comment('Value in grams');

//            $table->foreign('product_id')->references('id')->on('products')
//                ->cascadeOnDelete();
//
//            $table->foreign('ingredient_id')->references('id')->on('ingredients')
//                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_ingredients');
    }
};
