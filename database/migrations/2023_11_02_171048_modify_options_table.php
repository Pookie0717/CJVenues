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
         Schema::table('options', function (Blueprint $table) {
            // Add a 'logic' column
            $table->string('logic')->nullable();

            $table->enum('type', ['yes_no', 'check', 'radio', 'number', 'dropdown', 'logic'])->change();;

            // Add a 'description' column
            $table->string('description')->nullable();

            // Change 'season_id' to 'season_ids'
            $table->renameColumn('season_id', 'season_ids');

            // Change 'venue_id' to 'venue_ids'
            $table->renameColumn('venue_id', 'venue_ids');

            // Add a 'default_value' column
            $table->string('default_value')->nullable();

            // Add a 'vat' column as decimal with precision and scale
            $table->decimal('vat', 8, 2)->nullable();

            // Add an 'always_included' boolean column
            $table->boolean('always_included')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('options', function (Blueprint $table) {
            // Reverse the changes made in the 'up' method
            $table->dropColumn('logic');
            $table->enum('type', ['yes_no', 'check', 'radio', 'number', 'dropdown'])->change();;
            $table->dropColumn('description');
            $table->renameColumn('season_ids', 'season_id');
            $table->renameColumn('venue_ids', 'venue_id');
            $table->dropColumn('default_value');
            $table->dropColumn('vat');
            $table->dropColumn('always_included');
        });
    }
};
