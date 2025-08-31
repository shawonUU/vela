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
        Schema::create('product_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade');
            $table->foreignId('size_id')
                ->constrained('sizes')
                ->onDelete('cascade');
            $table->string('fit_type')->nullable();
            $table->string('color')->nullable();
            $table->integer('quantity')->default(0);
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->double('buying_price', 10, 2)->default(0.00);
            $table->double('selling_price', 10, 2)->default(0.00);
            $table->double('discounted_price', 10, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sizes');
    }
};
