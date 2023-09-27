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
        Schema::table('venue_areas', function (Blueprint $table) {
            $table->string('capacity_noseating')->nullable()->change();
            $table->string('capacity_seatingrows')->nullable()->change();
            $table->string('capacity_seatingtables')->nullable()->change();
        });
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('venue_areas', function (Blueprint $table) {
            $table->string('capacity_noseating')->nullable(false)->change();
            $table->string('capacity_seatingrows')->nullable(false)->change();
            $table->string('capacity_seatingtables')->nullable(false)->change();
        });
    }
};
