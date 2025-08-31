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
        Schema::table('invoice_details', function (Blueprint $table) {
            $table->double('tax',15,2)->default(0)->after('selling_price');
            $table->double('tax_rate',15,2)->default(0)->after('tax');
            $table->boolean('exchange')->default(0)->after('invoice_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_details', function(Blueprint $table){
            $table->dropColumn('tax');
            $table->dropColumn('tax_rate');
            $table->dropColumn('exchange');
        });
    }
};
