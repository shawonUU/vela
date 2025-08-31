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
            $table->double('bKash',16,2)->default(0)->after('master_card');
            $table->double('Nagad',16,2)->default(0)->after('bKash');
            $table->double('Rocket',16,2)->default(0)->after('Nagad');
            $table->double('Upay',16,2)->default(0)->after('Rocket');
            $table->double('SureCash',16,2)->default(0)->after('Upay');
            $table->double('online',16,2)->default(0)->after('SureCash');
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
            $table->dropColumn('bKash');
            $table->dropColumn('Nagad');
            $table->dropColumn('Rocket');
            $table->dropColumn('Upay');
            $table->dropColumn('SureCash');
            $table->dropColumn('online');
        });
    }
};
