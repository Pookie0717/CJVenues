<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('event_name');
            $table->string('typical_seating');
            $table->string('duration_type');
            $table->string('min_duration');
            $table->string('max_duration');
            $table->string('time_setup');
            $table->string('time_cleaningup');
            $table->unsignedBigInteger('season_id')->nullable()->default(null);
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
            $table->timestamps();
        });
    }


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_types');
    }
};
