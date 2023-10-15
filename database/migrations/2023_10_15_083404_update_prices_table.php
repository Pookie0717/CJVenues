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
        Schema::table('prices', function (Blueprint $table) {
            // Remove columns
            $table->dropColumn('tier_type');
            $table->dropColumn('tier_value');
            // Add new column
            $table->string('multiplier')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prices', function (Blueprint $table) {
            // Reverse the changes made in the "up" method
            $table->string('tier_type')->nullable();
            $table->integer('tier_value')->nullable();
            // Remove the new column
            $table->dropColumn('multiplier');
        });
    }
};
