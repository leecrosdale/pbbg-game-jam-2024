<?php

use App\Http\Controllers\GovernmentController;
use App\Http\Controllers\GovernmentInfrastructureController;
use App\Http\Controllers\GovernmentResourceController;
use App\Http\Controllers\GovernmentLeaderboardController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\ReportController;
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
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [GovernmentController::class, 'index'])->name('dashboard');

    Route::get('/government-infrastructure', [GovernmentInfrastructureController::class, 'index'])->name('client.government-infrastructure.index');
    Route::post('/government-infrastructure', [GovernmentInfrastructureController::class, 'store'])->name('client.government-infrastructure.store');
    Route::patch('/government-infrastructure/{governmentInfrastructure}', [GovernmentInfrastructureController::class, 'update'])->name('client.government-infrastructure.update');
    Route::post('/government-infrastructure/{governmentInfrastructure}/upgrade', [GovernmentInfrastructureController::class, 'upgrade'])->name('client.government-infrastructure.upgrade');

    Route::get('/government-resources', [GovernmentResourceController::class, 'index'])->name('client.government-resources.index');
    Route::patch('/government-resources/{governmentResource}', [GovernmentResourceController::class, 'update'])->name('client.government-resources.update');

    Route::patch('/government/population', [GovernmentController::class, 'populationUpdate'])->name('client.government.population.update');

    // New Policy Management Routes
    Route::get('/policies', [PolicyController::class, 'index'])->name('policies.index');
    Route::post('/policies/enact', [PolicyController::class, 'enact'])->name('policies.enact');
    Route::delete('/policies/{policy}', [PolicyController::class, 'cancel'])->name('policies.cancel');

    // New Reports Routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

Route::get('/leaderboard', [GovernmentLeaderboardController::class, 'index'])->name('leaderboard.index');

require __DIR__.'/auth.php';
