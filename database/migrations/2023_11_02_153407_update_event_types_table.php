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
            // Rename 'duration' column to 'max_duration'
            $table->renameColumn('duration', 'max_duration');

            // Add 'min_people' and 'max_people' columns
            $table->integer('min_people')->nullable();
            $table->integer('max_people')->nullable();

            // Add 'description' column
            $table->text('description')->nullable();

            // Change 'season_id' column to 'seasons' (array)
            //$table->dropColumn('season_id');
            $table->json('seasons')->nullable();

            // Add 'opening_time' and 'closing_time' columns
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();

            // Remove 'availability' column
            $table->dropColumn('availability');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_types', function (Blueprint $table) {
            // Reverse the changes in the 'up' method
            $table->renameColumn('max_duration', 'duration');
            $table->dropColumn('min_people');
            $table->dropColumn('max_people');
            $table->dropColumn('description');
            $table->dropColumn('seasons');
            $table->dropColumn('opening_time');
            $table->dropColumn('closing_time');
            //$table->string('availability');
        });
    }
};
