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
            // Change discount column to string
            $table->string('discount')->change();

            // Remove discount_type column
            $table->dropColumn('discount_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            // Change discount back to integer
            $table->integer('discount')->change();

            // Add discount_type column back
            $table->string('discount_type');
        });
    }
};
