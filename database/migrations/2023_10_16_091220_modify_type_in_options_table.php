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
            DB::statement("ALTER TABLE options MODIFY COLUMN type ENUM('yes_no','check','radio','number','dropdown')");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('options', function (Blueprint $table) {
            DB::statement("ALTER TABLE options MODIFY COLUMN type ENUM('yes_no','check','radio')");
        });
    }
};
