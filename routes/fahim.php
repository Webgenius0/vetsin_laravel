<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Backend\AgePreferenceController;
use App\Http\Controllers\Web\Backend\PreferedPropertyTypeController;
use App\Http\Controllers\Web\Backend\ChooseYourIdentityController;
use App\Http\Controllers\Web\Backend\BudgetController;

// Routes for Age Preference
Route::controller(AgePreferenceController::class)->group(function () {
    Route::get('/age-preference', 'index')->name('age_preference.index');
    Route::get('/age-preference/create', 'create')->name('age_preference.create');
    Route::post('/age-preference/store', 'store')->name('age_preference.store');
    Route::get('/age-preference/edit/{id}', 'edit')->name('age_preference.edit');
    Route::post('/age-preference/update/{id}', 'update')->name('age_preference.update');
    Route::get('/age-preference/status/{id}', 'status')->name('age_preference.status');
    Route::delete('/age-preference/destroy/{id}', 'destroy')->name('age_preference.destroy');
});

Route::controller(PreferedPropertyTypeController::class)->group(function () {
    Route::get('/prefered-property-type', 'index')->name('prefered_property_type.index');
    Route::get('/prefered-property-type/create', 'create')->name('prefered_property_type.create');
    Route::post('/prefered-property-type/store', 'store')->name('prefered_property_type.store');
    Route::get('/prefered-property-type/edit/{id}', 'edit')->name('prefered_property_type.edit');
    Route::post('/prefered-property-type/update/{id}', 'update')->name('prefered_property_type.update');
    Route::get('/prefered-property-type/status/{id}', 'status')->name('prefered_property_type.status');
    Route::delete('/prefered-property-type/destroy/{id}', 'destroy')->name('prefered_property_type.destroy');
});

Route::controller(ChooseYourIdentityController::class)->group(function () {
    Route::get('/choose-your-identity', 'index')->name('choose_your_identity.index');
    Route::get('/choose-your-identity/create', 'create')->name('choose_your_identity.create');
    Route::post('/choose-your-identity/store', 'store')->name('choose_your_identity.store');
    Route::get('/choose-your-identity/edit/{id}', 'edit')->name('choose_your_identity.edit');
    Route::post('/choose-your-identity/update/{id}', 'update')->name('choose_your_identity.update');
    Route::get('/choose-your-identity/status/{id}', 'status')->name('choose_your_identity.status');
    Route::delete('/choose-your-identity/destroy/{id}', 'destroy')->name('choose_your_identity.destroy');
});


Route::controller(BudgetController::class)->group(function () {
    Route::get('/budget', 'index')->name('budget.index');
    Route::get('/budget/create', 'create')->name('budget.create');
    Route::post('/budget/store', 'store')->name('budget.store');
    Route::get('/budget/edit/{id}', 'edit')->name('budget.edit');
    Route::post('/budget/update/{id}', 'update')->name('budget.update');
    Route::get('/budget/status/{id}', 'status')->name('budget.status');
    Route::delete('/budget/destroy/{id}', 'destroy')->name('budget.destroy');
});
