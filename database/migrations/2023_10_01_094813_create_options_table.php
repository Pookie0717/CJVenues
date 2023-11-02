<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('position')->unsigned();
            $table->enum('type', ['yes_no', 'check', 'radio', 'number', 'dropdown', 'logic']);
            $table->text('values')->nullable(); // Use a text column to store | separated values
            $table->text('options_ids')->nullable();
            $table->text('options_values')->nullable();
            $table->string('logic')->nullable();
            $table->string('description')->nullable();
            $table->text('season_ids')->nullable();
            $table->text('venue_ids')->nullable();
            $table->string('default_value')->nullable();
            $table->decimal('vat', 8, 2)->nullable();
            $table->boolean('always_included')->default(false);
            $table->timestamps();
            // Add an index on the "position" column for performance
            $table->index('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('options');
    }
}

