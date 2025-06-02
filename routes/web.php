<?php

use Illuminate\Support\Facades\Route;

use \App\Http\Controllers\GameController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::get('/galgje', [GameController::class, 'bladeGame'])->name('galgje.view');
Route::post('/galgje/start', [GameController::class, 'startGameBlade'])->name('galgje.start');
Route::post('/galgje/{game}/guess', [GameController::class, 'guessLetterBlade'])->name('galgje.guess');
