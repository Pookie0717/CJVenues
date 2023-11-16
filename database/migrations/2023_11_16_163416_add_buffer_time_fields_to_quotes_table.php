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
            $table->integer('buffer_time_before')->nullable();
            $table->integer('buffer_time_after')->nullable();
            $table->string('buffer_time_unit')->nullable(); // 'hours' or 'days'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('buffer_time_before');
            $table->dropColumn('buffer_time_after');
            $table->dropColumn('buffer_time_unit');
        });
    }
};
