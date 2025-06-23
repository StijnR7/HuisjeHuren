<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/galgje', [GameController::class, 'bladeGame'])->name('galgje.view');
    Route::post('/galgje/start', [GameController::class, 'startGameBlade'])->name('galgje.start');
    Route::post('/galgje/guess/{game}', [GameController::class, 'guessLetterBlade'])->name('galgje.guess');
});

require __DIR__.'/auth.php';
