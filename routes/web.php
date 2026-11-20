<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

$centralDomains = array_values(array_filter((array) config('tenancy.central_domains')));
$canonicalDomain = array_shift($centralDomains);

// Register the canonical domain first so named routes always generate its URL.
foreach ([$canonicalDomain, ...$centralDomains] as $domain) {
    Route::domain($domain)->group(function (): void {
        Route::get('/', function () {
            return view('welcome');
        });

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->middleware(['auth', 'verified'])
            ->name('dashboard');

        Route::middleware('auth')->group(function () {
            Route::get('/activity', [ActivityController::class, 'index'])->name('activity.index');

            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        });

        require __DIR__.'/auth.php';
    });
}
