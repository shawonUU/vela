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
        Schema::create('pay_to_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');               // User name
            $table->string('email')->unique();    // Optional email
            $table->string('phone')->nullable();  // Optional phone
            $table->text('address')->nullable();  // Optional address
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_to_users');
    }
};
