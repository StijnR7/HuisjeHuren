<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    // Zorg dat Laravel deze velden mag vullen via mass assignment
    protected $fillable = [
        'word',
        'guessed_letters',
        'incorrect_guesses',
        'is_finished',
        'is_won',
    ];
}
