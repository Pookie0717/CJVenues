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
                Schema::table('prices', function (Blueprint $table) {
                    DB::statement('ALTER TABLE prices MODIFY COLUMN type ENUM("area", "option", "venue", "per_person", "pp_tier") NOT NULL DEFAULT "venue"');
                    $table->string('tier_type')->nullable();
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
                Schema::table('prices', function (Blueprint $table) {
                    DB::statement('ALTER TABLE prices MODIFY COLUMN type ENUM("area", "option", "venue") NOT NULL DEFAULT "venue"');
                    $table->dropColumn('tier_type');
                });
    }
};
