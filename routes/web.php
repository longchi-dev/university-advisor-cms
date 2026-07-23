<?php

use App\Enums\UserRoleEnum;
use App\Http\Controllers\CrawlDataController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataAdvisorController;
use App\Http\Controllers\EmbeddingController;
use App\Http\Controllers\ImportDataController;
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
    Route::get('/users/{id}/profile', [UserController::class, 'showProfile'])->name('users.profile');

    Route::get('/settings', [SettingController::class, 'index'])->name('setting.index');
    Route::patch('/settings', [SettingController::class, 'update'])->name('setting.update');

    Route::get('/data-advisor', [DataAdvisorController::class, 'index'])->name('data-advisor.index');

    Route::get('/llm-log', [LlmLogController::class, 'index'])->name('llm-log.index');
    Route::resource('/llm-keys', LLMKeyController::class);

    Route::get('/crawl-data', [CrawlDataController::class, 'index'])->name('crawl-data.index');
    Route::post('/crawl-data/crawl', [CrawlDataController::class, 'crawl'])->name('crawl-data.crawl');
    Route::get('/crawl-data/status/{jobId}', [CrawlDataController::class, 'crawlStatus'])->name('crawl-data.status');

    Route::get('/import-data', [ImportDataController::class, 'index'])->name('import-data.index');
    Route::post('/import-data/import', [ImportDataController::class, 'import'])->name('import-data.import');
    Route::get('/import-data/status/{jobId}', [ImportDataController::class, 'importStatus'])->name('import-data.status');

    Route::get('/embedding', [EmbeddingController::class, 'index'])->name('embedding.index');
    Route::post('/embedding/generate', [EmbeddingController::class, 'import'])->name('embedding.generate');
    Route::get('/embedding/status/{jobId}', [EmbeddingController::class, 'importStatus'])->name('embedding.status');

    Route::get('/setting', [SettingController::class, 'index'])->name('setting');
    Route::patch('/setting', [SettingController::class, 'update'])->name('setting.update');

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
