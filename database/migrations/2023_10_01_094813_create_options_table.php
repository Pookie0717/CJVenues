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
            $table->enum('type', ['yes_no', 'check', 'radio']);
            $table->text('values')->nullable(); // Use a text column to store | separated values
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

