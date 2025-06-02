<?php

use App\Http\Controllers\GameController;

Route::post('/games', [GameController::class, 'startGame']);
Route::post('/games/{game}/guess', [GameController::class, 'guessLetter']);
Route::get('/games/{game}', [GameController::class, 'show']);
