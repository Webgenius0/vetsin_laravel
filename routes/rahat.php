<?php

use App\Http\Controllers\Web\Backend\Dynamic_Input\IdealConnectionController;
use App\Http\Controllers\Web\Backend\Dynamic_Input\WillingToRelocateController;
use Illuminate\Support\Facades\Route;

// Willing To Relocate
Route::controller(WillingToRelocateController::class)->group(function () {
    Route::get('/willing-to-relocate', 'index')->name('admin.willing_to_relocate.index');
    Route::get('/willing-to-relocate/create', 'create')->name('admin.willing_to_relocate.create');
    Route::post('/willing-to-relocate/store', 'store')->name('admin.willing_to_relocate.store');
    Route::get('/willing-to-relocate/edit/{id}', 'edit')->name('admin.willing_to_relocate.edit');
    Route::post('/willing-to-relocate/update/{id}', 'update')->name('admin.willing_to_relocate.update');
    Route::get('/willing-to-relocate/status/{id}', 'status')->name('admin.willing_to_relocate.status');
    Route::delete('/willing-to-relocate/destroy/{id}', 'destroy')->name('admin.willing_to_relocate.destroy');
});

// Ideal Connection
Route::controller(IdealConnectionController::class)->group(function () {
    Route::get('/ideal-connection', 'index')->name('admin.ideal_connection.index');
    Route::get('/ideal-connection/create', 'create')->name('admin.ideal_connection.create');
    Route::post('/ideal-connection/store', 'store')->name('admin.ideal_connection.store');
    Route::get('/ideal-connection/edit/{id}', 'edit')->name('admin.ideal_connection.edit');
    Route::post('/ideal-connection/update/{id}', 'update')->name('admin.ideal_connection.update');
    Route::get('/ideal-connection/status/{id}', 'status')->name('admin.ideal_connection.status');
    Route::delete('/ideal-connection/destroy/{id}', 'destroy')->name('admin.ideal_connection.destroy');
});
