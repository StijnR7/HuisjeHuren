<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Game extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
 public function up()
{
    Schema::create('games', function (Blueprint $table) {
        $table->id();
        $table->string('word'); // het te raden woord
        $table->string('guessed_letters')->nullable(); // opgeslagen als string, bv. "aeiou"
        $table->unsignedInteger('incorrect_guesses')->default(0);
        $table->boolean('is_finished')->default(false);
        $table->boolean('is_won')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
