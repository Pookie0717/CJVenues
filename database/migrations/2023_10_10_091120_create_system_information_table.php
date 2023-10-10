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
        Schema::create('system_information', function (Blueprint $table) {
            $table->id();
            $table->timestamp('last_access')->nullable();
            $table->integer('number_of_users')->nullable();
            $table->integer('current_quote_number')->nullable()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_information');
    }
};
