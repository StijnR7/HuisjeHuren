<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsStartedPregame extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
{
    Schema::table('pre_games', function (Blueprint $table) {
        $table->boolean('is_started')->default(false);
        $table->unsignedBigInteger('plr2')->nullable();
        $table->unsignedBigInteger('game_id')->nullable();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
   public function down()
{
    Schema::table('pre_games', function (Blueprint $table) {
        $table->dropColumn(['is_started', 'plr2', 'game_id']);
    });
}
}
