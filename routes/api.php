<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GamingSessionController;
use App\Http\Controllers\LeaderBoardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::post('export', [DashboardController::class, 'export'])->name('export');
    Route::get('export/status/{id}', [DashboardController::class, 'status'])->name('export.status');

    // Export Gaming Session CSV
    Route::post('export-gaming-session', [GamingSessionController::class, 'export'])->name('export-gaming-session');
    Route::get('export-gaming-session/status/{id}', [DashboardController::class, 'status'])->name('export-gaming-session.status');

    // Import Leaderboard Excel
    Route::post('import-leaderboard-data', [LeaderBoardController::class, 'import'])->name('import-leaderboard-data');
    Route::get('import-leaderboard-data/status/{id}', [LeaderBoardController::class, 'importStatus'])->name('import-leaderboard-data.status');
});
