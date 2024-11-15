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

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/leaderboard', [\App\Http\Controllers\GovernmentLeaderboardController::class, 'index'])->name('leaderboard.index');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', [\App\Http\Controllers\GovernmentController::class, 'index'])->name('dashboard');

    Route::group(['as' => 'client.'], function () {
        Route::resource('government-infrastructure', \App\Http\Controllers\GovernmentInfrastructureController::class);
        Route::post('government-infrastrucutre/{government_infrastructure}/upgrade', [\App\Http\Controllers\GovernmentInfrastructureController::class, 'upgrade'])->name('government-infrastructure.upgrade');
        Route::resource('government-resources', \App\Http\Controllers\GovernmentResourceController::class);
        Route::patch('government/population', [GovernmentController::class, 'populationUpdate'])->name('government.population.update');
        Route::get('government', [GovernmentController::class, 'index'])->name('government.index');
    });
});

require __DIR__.'/auth.php';
