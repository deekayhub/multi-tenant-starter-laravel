<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TenantController as AdminTenantController;
use App\Http\Controllers\Admin\TenantImpersonationController;
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

            Route::get('/profile', [ProfileController::class, 'editCentral'])->name('central.profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('central.profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('central.profile.destroy');
        });

        Route::middleware(['auth', 'ensure.super_admin'])
            ->prefix('admin')
            ->as('admin.')
            ->group(function (): void {
                Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

                Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs');

                Route::get('tenants/{tenant}/impersonate', TenantImpersonationController::class)
                    ->name('tenants.impersonate');

                Route::patch('tenants/{tenant}/toggle-status', [AdminTenantController::class, 'toggleStatus'])
                    ->name('tenants.toggle-status');

                Route::resource('tenants', AdminTenantController::class)
                    ->except(['edit', 'update']);
            });

        require __DIR__.'/auth.php';
    });
}
