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
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postcode', 10)->nullable();
            $table->string('stateprovince')->nullable();
            $table->string('country')->nullable();
            $table->string('currency', 10)->nullable();
            $table->string('vatnumber', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('city');
            $table->dropColumn('postcode');
            $table->dropColumn('stateprovince');
            $table->dropColumn('country');
            $table->dropColumn('currency');
            $table->dropColumn('vatnumber');
        });
    }
};
