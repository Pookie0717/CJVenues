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
        Schema::table('event_types', function (Blueprint $table) {
            $table->integer('min_buffer_before')->nullable();
            $table->integer('max_buffer_before')->nullable();
            $table->integer('min_buffer_after')->nullable();
            $table->integer('max_buffer_after')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_types', function (Blueprint $table) {
            $table->dropColumn('min_buffer_before');
            $table->dropColumn('max_buffer_before');
            $table->dropColumn('min_buffer_after');
            $table->dropColumn('max_buffer_after');
        });
    }
};
