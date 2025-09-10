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
        Schema::table('payments',function(Blueprint $table){
            // All payment type
            $table->double('cash',16,2)->default(0)->after('paid_amount');
            $table->double('visa_card',16,2)->default(0)->after('cash');
            $table->double('master_card',16,2)->default(0)->after('visa_card');
            $table->double('bkash',16,2)->default(0)->after('master_card');
            $table->double('nagad',16,2)->default(0)->after('bkash');
            $table->double('rocket',16,2)->default(0)->after('nagad');
            $table->double('upay',16,2)->default(0)->after('rocket');
            $table->double('surecash',16,2)->default(0)->after('upay');
            $table->double('online',16,2)->default(0)->after('surecash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments',function(Blueprint $table){
            $table->dropColumn('cash');
            $table->dropColumn('visa_card');
            $table->dropColumn('master_card');
            $table->dropColumn('bkash');
            $table->dropColumn('nagad');
            $table->dropColumn('rocket');
            $table->dropColumn('upay');
            $table->dropColumn('surecash');
            $table->dropColumn('online');
        });
    }
};
