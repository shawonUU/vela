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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('article_id')->index();       // Article ID
            $table->unsignedBigInteger('category_id')->index();      // Category ID
            $table->decimal('amount', 12, 2)->default(0);            // Amount
            $table->date('date')->index();                            // Expense date
            $table->text('note')->nullable();                         // Notes
            $table->string('pay_to')->nullable();                     // Pay to
            $table->enum('payment_method', ['cash', 'visa_card', 'master_card', 'bkash', 'nagad', 'rocket', 'upay','surecash','online'])->nullable(); // Payment method
            $table->enum('is_approved', ['0', '1'])->default('0');   // Approval status
            $table->unsignedBigInteger('created_by')->index();        // Created by
            $table->unsignedBigInteger('updated_by')->nullable()->index(); // Updated by
            $table->unsignedBigInteger('business_day_id');           // Business day ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
