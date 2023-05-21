<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BingoBoardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard
Route::prefix('dashboard')
    ->middleware(['auth', 'verified'])
    ->group(function() {
        // Dashboard
        Route::get('/', function() {
            return view('dashboard');
        })->name('dashboard');

        // Events
        Route::prefix('events')->group(function() {
            Route::get('/create', [EventController::class, 'create'])->name('events.create');
            Route::post('/store', [EventController::class, 'store'])->name('events.store');
            Route::get('/{event}', [EventController::class, 'show'])->name('events.show');
            Route::post('/{event}/join', [EventController::class, 'join'])->name('events.join');
            Route::post('/{event}/leave', [EventController::class, 'leave'])->name('events.leave');
        });

        // Bingo Boards
        Route::prefix('boards')->group(function() {
            Route::get('/create', [BingoBoardController::class, 'create'])->name('boards.create');
            Route::post('/store', [BingoBoardController::class, 'store'])->name('boards.store');
        });
});


require __DIR__.'/auth.php';
