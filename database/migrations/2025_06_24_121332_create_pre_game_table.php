<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreGameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_games', function (Blueprint $table) {
            $table->id();
            
            $table->timestamps();
            $table->string('players')->default(1); // Store player names or IDs
            $table->unsignedBigInteger('plr1'); // ID of player 1
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pre_games');
    }
}
