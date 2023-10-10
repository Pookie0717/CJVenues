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
        Schema::table('system_information', function (Blueprint $table) {
            // Drop existing columns
            $table->dropColumn(['last_access', 'number_of_users', 'current_quote_number']);

            // Add key and value columns
            $table->string('key')->unique();
            $table->integer('value')->default(0);
        });

        // Populate the table with default values for existing keys
        DB::table('system_information')->insert([
            ['key' => 'last_access', 'value' => 0],
            ['key' => 'number_of_users', 'value' => 0],
            ['key' => 'current_quote_number', 'value' => 0],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_information', function (Blueprint $table) {
            // Drop the key and value columns
            $table->dropColumn(['key', 'value']);

            // Recreate the original columns
            $table->timestamp('last_access')->nullable();
            $table->integer('number_of_users')->nullable();
            $table->integer('current_quote_number')->default(1);
        });
    }
};