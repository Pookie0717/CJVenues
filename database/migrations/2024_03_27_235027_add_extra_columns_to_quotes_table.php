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
            $table->string('options_count')->nullable();
            $table->string('extra_items_name')->nullable();
            $table->string('extra_items_count')->nullable();
            $table->string('extra_items_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('options_count');
            $table->dropColumn('extra_items_name');
            $table->dropColumn('extra_items_count');
            $table->dropColumn('extra_items_price');
        });
    }
};
