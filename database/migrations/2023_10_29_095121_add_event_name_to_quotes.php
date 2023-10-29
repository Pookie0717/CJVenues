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
        Schema::table('quotes', function (Blueprint $table) {
            $table->string('event_name')->nullable()->after('event_type'); // Change 'other_column_name' to the appropriate column name
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('event_name');
        });
    }
};
