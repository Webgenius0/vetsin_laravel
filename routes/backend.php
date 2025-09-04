<?php

use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\FaqController;
use App\Http\Controllers\Web\Backend\FavoriteInvestingMarketController;
use App\Http\Controllers\Web\Backend\FunPromptController;
use App\Http\Controllers\Web\Backend\HashTagsController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/testNotification', [DashboardController::class, 'testNotification']);

//FAQ Routes
Route::controller(FaqController::class)->group(function () {
    Route::get('/faqs', 'index')->name('admin.faqs.index');
    Route::get('/faqs/create', 'create')->name('admin.faqs.create');
    Route::post('/faqs/store', 'store')->name('admin.faqs.store');
    Route::get('/faqs/edit/{id}', 'edit')->name('admin.faqs.edit');
    Route::post('/faqs/update/{id}', 'update')->name('admin.faqs.update');
    Route::post('/faqs/status/{id}', 'status')->name('admin.faqs.status');
    Route::post('/faqs/destroy/{id}', 'destroy')->name('admin.faqs.destroy');
});

Route::prefix('fun-prompts/{type}')->group(function () {
    Route::get('/', [FunPromptController::class, 'index'])->name('fun-prompts.index');
    Route::get('/create', [FunPromptController::class, 'create'])->name('fun-prompts.create');
    Route::post('/', [FunPromptController::class, 'store'])->name('fun-prompts.store');
    Route::get('/{id}/edit', [FunPromptController::class, 'edit'])->name('fun-prompts.edit');
    Route::put('/{id}', [FunPromptController::class, 'update'])->name('fun-prompts.update');
    Route::delete('/{id}', [FunPromptController::class, 'destroy'])->name('fun-prompts.destroy');
    Route::post('/{id}/status', [FunPromptController::class, 'status'])->name('fun-prompts.status');
});

Route::controller(FavoriteInvestingMarketController::class)->group(function () {
    Route::get('/favorite-investing-markets', 'index')->name('favorite-investing-markets.index');
    Route::get('/favorite-investing-markets/create', 'create')->name('favorite-investing-markets.create');
    Route::post('/favorite-investing-markets/store', 'store')->name('favorite-investing-markets.store');
    Route::get('/favorite-investing-markets/edit/{id}', 'edit')->name('favorite-investing-markets.edit');
    Route::post('/favorite-investing-markets/update/{id}', 'update')->name('favorite-investing-markets.update');
    Route::post('/favorite-investing-markets/status/{id}', 'status')->name('favorite-investing-markets.status');
    Route::post('/favorite-investing-markets/destroy/{id}', 'destroy')->name('favorite-investing-markets.destroy');
});

Route::controller(HashTagsController::class)->group(function () {
    Route::get('/hash-tags', 'index')->name('hash-tags.index');
    Route::get('/hash-tags/create', 'create')->name('hash-tags.create');
    Route::post('/hash-tags/store', 'store')->name('hash-tags.store');
    Route::get('/hash-tags/edit/{id}', 'edit')->name('hash-tags.edit');
    Route::post('/hash-tags/update/{id}', 'update')->name('hash-tags.update');
    Route::post('/hash-tags/status/{id}', 'status')->name('hash-tags.status');
    Route::post('/hash-tags/destroy/{id}', 'destroy')->name('hash-tags.destroy');
});
