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
        Schema::create('expense_articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expense_category_id');   // FK to expense_categories
            $table->string('name');                               // Article name
            $table->text('notes')->nullable();                    // Extra description / notes
            $table->boolean('status')->default(1);                // 1 = Active, 0 = Inactive
            $table->boolean('is_approved')->default(0);           // 0 = Not approved, 1 = Approved
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_articles');
    }
};
