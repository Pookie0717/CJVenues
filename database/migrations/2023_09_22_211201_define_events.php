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
        Schema::create('events_type', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('typical_seating');
            $table->string('duration_type');
            $table->string('duration');
            $table->string('min_duration');
            $table->string('time_setup');
            $table->string('time_cleaningup');
            $table->unsignedBigInteger('season_id');  // Foreign key to associate with a period
            $table->string('availability');
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
        Schema::dropIfExists('events_type');
    }
};
