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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_id');  // Foreign key to associate with a contact
            $table->string('status');
            $table->string('version');
            $table->string('date_from');
            $table->string('date_to');
            $table->string('time_from');
            $table->string('time_to');
            $table->unsignedBigInteger('area_id');  // Foreign key to associate with an area
            $table->string('event_type');
            $table->timestamps();

            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->foreign('area_id')->references('id')->on('venue_areas');
        });
    }


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotes');
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign(['contact_id']);
            $table->dropForeign(['area_id']);

        });
    }
};
