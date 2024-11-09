<?php

use App\Http\Controllers\GovernmentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('dashboard', [\App\Http\Controllers\GovernmentController::class, 'index'])->name('dashboard');


    Route::group(['as' => 'client.'], function () {
        Route::resource('government-infrastructure', \App\Http\Controllers\GovernmentInfrastructureController::class);


        Route::patch('government/population', [GovernmentController::class, 'populationUpdate'])->name('government.population.update');
    });



});

require __DIR__.'/auth.php';
