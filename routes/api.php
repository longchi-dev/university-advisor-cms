<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::post('export', [DashboardController::class, 'export'])->name('export');
    Route::get('export/status/{id}', [DashboardController::class, 'status'])->name('export.status');
});
