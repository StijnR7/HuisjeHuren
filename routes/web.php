<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/galgje', [GameController::class, 'bladeGame'])->name('galgje.view');
    Route::post('/galgje/start', [GameController::class, 'StartLookingForGame'])->name('galgje.start');
    Route::post('/galgje/guess', [GameController::class, 'guessLetterBlade'])->name('galgje.guess');
    Route::get('/galgje/game-data', [GameController::class, 'gameData'])->name('galgje.data');
    Route::get('/galgje/realStartGame/{id1}/{id2}', [GameController::class, 'startGameBlade'])->name('galgje.realStartGame');
    Route::get('/galgje/wait/{id}', [GameController::class, 'waitForPlayer'])->name('galgje.waitForPlayer');
Route::get('/galgje/realStartGame/{id1}/{id2}/{preGameId}', [GameController::class, 'startGameBlade'])->name('galgje.realStartGame');

});

require __DIR__.'/auth.php';
