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
        Schema::table('options', function (Blueprint $table) {
            // Remove the options_ids column
            $table->dropColumn('options_ids');
            
            // Remove the options_values column
            $table->dropColumn('options_values');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('options', function (Blueprint $table) {
            // Add back the options_ids column
            $table->string('options_ids')->nullable();
            
            // Add back the options_values column
            $table->string('options_values')->nullable();
        });
    }
};
