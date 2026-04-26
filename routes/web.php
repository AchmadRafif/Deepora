<?php

use App\Http\Controllers\FocusRoomController;
use App\Http\Controllers\PomodoroController;
use App\Http\Controllers\LeaderboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Focus Rooms
    Route::resource('rooms', FocusRoomController::class);

    // Pomodoro
    Route::post('/pomodoro/complete', [PomodoroController::class, 'complete'])->name('pomodoro.complete');

    // Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Chat
    Route::get('/rooms/{roomId}/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/rooms/{roomId}/chat', [ChatController::class, 'store'])->name('chat.store');
});

require __DIR__ . '/auth.php';
