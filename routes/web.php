<?php
use App\Http\Controllers\FocusRoomController;
use App\Http\Controllers\PomodoroController;
use App\Http\Controllers\LeaderboardController;
use Illuminate\Support\Facades\Route;

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
});

require __DIR__.'/auth.php';