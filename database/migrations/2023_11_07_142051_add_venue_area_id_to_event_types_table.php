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
            $table->unsignedBigInteger('venue_area_id')->nullable();
            $table->foreign('venue_area_id')->references('id')->on('venue_areas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('event_types', function (Blueprint $table) {
            $table->dropForeign(['venue_area_id']);
            $table->dropColumn('venue_area_id');
        });
    }
};
