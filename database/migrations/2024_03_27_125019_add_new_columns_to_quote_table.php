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
        Schema::table('quotes', function (Blueprint $table) {
            $table->string('options_name')->nullable();
            $table->string('venues_name')->nullable();
            $table->string('staffs_name')->nullable();
            $table->string('details', 10000)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('details');
            $table->dropColumn('staffs_name');
            $table->dropColumn('venues_name');
            $table->dropColumn('options_name');
        });
    }
};
