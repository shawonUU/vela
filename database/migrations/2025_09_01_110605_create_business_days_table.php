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
        Schema::create('business_days', function (Blueprint $table) {
            $table->id();
            $table->date('business_date');
            $table->timestamp('opening_time');
            $table->timestamp('closing_time')->nullable();
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('closing_balance', 15, 2)->default(0); 

            // Payment methods close
            $table->double('opening_cash', 15, 2)->default(0);
            $table->double('opening_visa_card', 15, 2)->default(0);
            $table->double('opening_master_card', 15, 2)->default(0);
            $table->double('opening_bkash', 15, 2)->default(0);
            $table->double('opening_nagad', 15, 2)->default(0);
            $table->double('opening_rocket', 15, 2)->default(0);
            $table->double('opening_upay', 15, 2)->default(0);
            $table->double('opening_surecash', 15, 2)->default(0);
            $table->double('opening_online', 15, 2)->default(0);

            // Payment methods for close
            $table->double('closing_cash', 15, 2)->default(0);
            $table->double('closing_visa_card', 15, 2)->default(0);
            $table->double('closing_master_card', 15, 2)->default(0);
            $table->double('closing_bkash', 15, 2)->default(0);
            $table->double('closing_nagad', 15, 2)->default(0);
            $table->double('closing_rocket', 15, 2)->default(0);
            $table->double('closing_upay', 15, 2)->default(0);
            $table->double('closing_surecash', 15, 2)->default(0);
            $table->double('closing_online', 15, 2)->default(0);

            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_days');
    }
};
