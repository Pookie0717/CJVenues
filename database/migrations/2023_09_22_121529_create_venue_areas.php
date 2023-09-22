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
        Schema::create('venue_areas', function (Blueprint $table) {
            $table->id();
            $table->string('venue_id');
            $table->string('name');
            $table->string('capacity_noseating');
            $table->string('capacity_seatingrows');
            $table->string('capacity_seatingtables');
            $table->timestamps();
        });    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('venue_areas');
    }
};
