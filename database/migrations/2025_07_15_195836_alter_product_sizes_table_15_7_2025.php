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
        Schema::table('product_sizes', function(Blueprint $table){
            $table->double('wholesell_price',10,2)->default(0)->after('selling_price');
            $table->double('wholesell_discounted_price',10,2)->default(0)->after('wholesell_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_sizes',function(Blueprint $table){
            $table->dropColumn('wholesell_price');
            $table->dropColumn('wholesell_discounted_price');
        });
    }
};
