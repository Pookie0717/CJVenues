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
            $table->string('date_from')->nullable()->change();
            $table->string('date_to')->nullable()->change();
            $table->string('time_from')->nullable()->change();
            $table->string('time_to')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->string('date_from')->nullable(false)->change();
            $table->string('date_to')->nullable(false)->change();
            $table->string('time_from')->nullable(false)->change();
            $table->string('time_to')->nullable(false)->change();
        });;
    }
};
