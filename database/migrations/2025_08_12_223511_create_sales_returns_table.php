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
        Schema::create('sales_returns', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('invoice_detail_id');
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('return_invoice_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('product_size_id');

            $table->date('buying_date');
            $table->date('return_date');
            $table->decimal('return_qty', 10, 2);
            $table->decimal('unit_price', 10, 2);

            $table->string('discount_type')->nullable();
            $table->decimal('discount_rate', 10, 2)->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable();

            $table->string('tax_type')->nullable();
            $table->decimal('tax_rate', 10, 2)->nullable();
            $table->decimal('tax_amount', 10, 2)->nullable();

            $table->decimal('buying_price', 10, 2)->nullable();
            $table->decimal('selling_price', 10, 2)->nullable();

            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('business_day_id');

            $table->timestamps();

            // Foreign keys (optional if you have related tables)
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('invoice_detail_id')->references('id')->on('invoice_details')->onDelete('cascade');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('product_size_id')->references('id')->on('product_sizes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_returns');
    }
};
