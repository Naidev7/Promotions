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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->string('category');
            $table->integer('price');
            $table->integer('final_price');
            $table->string('discount_percentage')->nullable(); //When a product does not have a discount, `price.final` and `price.original` should be the same number and `discount_percentage` should be null.
            $table->string('currency')->default('EUR'); //`price.currency` is always `EUR`
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
