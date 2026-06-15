<?php

use App\Enums\UserRoleEnum;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LLMKeyController;
use App\Http\Controllers\LlmLogController;
use App\Http\Controllers\GamingSessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    Route::get('/settings', [SettingController::class, 'index'])->name('setting.index');
    Route::patch('/settings', [SettingController::class, 'update'])->name('setting.update');

//    Route::resource('prompt-randoms', PromptRandomController::class);
//
    Route::get('/llm-log', [LlmLogController::class, 'index'])->name('llm-log.index');
    Route::resource('/llm-keys', LLMKeyController::class);

//    Route::middleware('role:' .UserRoleEnum::SETTING->value)->group(function () {
//        Route::get('/settings', [SettingController::class, 'index'])->name('setting.index');
//        Route::patch('/settings', [SettingController::class, 'update'])->name('setting.update');
//    });

//    Route::middleware('role:' .UserRoleEnum::ADMIN->value)->group(function (){
//        Route::get('/llm_log', [LlmLogController::class, 'index'])->name('llm_log.index');
//        Route::resource('gemini-keys', GeminiKeyController::class);
//    });
});
require __DIR__.'/auth.php';
