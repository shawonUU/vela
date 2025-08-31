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
        Schema::table('products', function (Blueprint $table) {
            $table->date('production_date')->nullable()->after('product_sort_name');
            $table->date('expire_date')->nullable()->after('production_date');
            $table->string('fabrics')->nullable()->after('expire_date');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('production_date');
            $table->dropColumn('expire_date');
            $table->dropColumn('fabrics');
        });
    }
};
