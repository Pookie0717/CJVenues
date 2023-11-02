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
        Schema::create('blocked_areas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('area_id'); // Foreign key to reference blocked area
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps(); // Created_at and Updated_at timestamps

            // Define foreign key constraint
            $table->foreign('area_id')
                ->references('id')
                ->on('venue_areas')
                ->onDelete('cascade'); // Optional: Define the delete action
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_areas');
    }
};