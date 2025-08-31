<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('cashier_id')->nullable();
            
            $table->decimal('opening_balance', 15, 2)->default(0); 
            $table->decimal('total_sales', 15, 2)->default(0); 
            $table->decimal('total_expenses', 15, 2)->default(0); 
            $table->decimal('closing_balance', 15, 2)->default(0); 
            
            $table->enum('status', ['open', 'closed'])->default('open'); 
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_registers');
    }
};
