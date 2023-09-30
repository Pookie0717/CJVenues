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
        // Add new columns
        Schema::table('prices', function (Blueprint $table) {
            $table->enum('type', ['area', 'option', 'venue']);
            $table->unsignedBigInteger('venue_id')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->unsignedBigInteger('option_id')->nullable();
            $table->decimal('price', 10, 2)->nullable(); // Modify data type and precision as needed
            $table->string('tier_type')->nullable(); // "t" for tier or null
            $table->integer('tier_value')->nullable(); // Number for tier or null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
                Schema::dropIfExists('prices');

    }
};
