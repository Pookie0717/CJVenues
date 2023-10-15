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
            $table->decimal('calculated_price', 10, 2)->nullable();
            $table->string('discount_type')->nullable();
            $table->integer('discount')->nullable();
            $table->decimal('price', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('calculated_price');
            $table->dropColumn('discount_type');
            $table->dropColumn('discount');
            $table->dropColumn('price');
        });
    }
};