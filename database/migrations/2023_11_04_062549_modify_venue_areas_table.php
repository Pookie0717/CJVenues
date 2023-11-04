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
        Schema::table('venue_areas', function (Blueprint $table) {
            // Modify the venue_id column to be an unsigned big integer
            $table->unsignedBigInteger('venue_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('venue_areas', function (Blueprint $table) {
            // If you ever need to rollback, you can revert the change here
            // This may not be necessary depending on your application's needs
            $table->string('venue_id', 191)->change();
        });
    }
};
