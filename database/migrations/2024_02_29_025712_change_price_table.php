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
            $table->dropForeign('prices_season_id_foreign');
            $table->dropColumn('season_id');
            $table->string('season_ids')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prices', function (Blueprint $table) {
            $table->dropForeign('season_ids')->nullable();
            $table->unsignedBigInteger('season_id');
            $table->foreign('prices_season_id_foreign');
        });
    }
};
