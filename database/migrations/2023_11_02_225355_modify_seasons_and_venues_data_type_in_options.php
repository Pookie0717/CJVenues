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
            $table->dropForeign(['season_id']);
            $table->dropForeign(['venue_id']);
            $table->text('season_ids')->nullable();
            $table->text('venue_ids')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('options', function (Blueprint $table) {
            // Add back the foreign key constraints
            $table->foreign('season_id')->references('id')->on('seasons');
            $table->foreign('venue_id')->references('id')->on('venues');

            // Revert the column types to what they were before
            $table->dropColumn('season_ids');
            $table->dropColumn('venue_ids');
        });
    }
};