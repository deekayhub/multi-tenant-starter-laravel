<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Tenant\LoginController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Middleware\EnsureTenantIsActive;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Features\UserImpersonation;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    PreventAccessFromCentralDomains::class,
    InitializeTenancyByDomainOrSubdomain::class,
    EnsureTenantIsActive::class,
])->group(function (): void {
    Route::get('/impersonate/{token}', fn (string $token) => UserImpersonation::makeResponse($token))
        ->name('tenant.impersonate');

    Route::get('/login/{token}', LoginController::class)
        ->middleware('throttle:6,1')
        ->name('tenant.login');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->middleware('signed')
        ->name('logout');

    Route::get('/', [DashboardController::class, 'index'])
        ->middleware('auth')
        ->name('tenant.dashboard');

    Route::middleware('auth')->group(function (): void {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('verification.send');
        Route::put('password', [PasswordController::class, 'update'])->name('password.update');

        Route::get('/activity', [ActivityController::class, 'index'])
            ->middleware('permission:activity-logs.view')
            ->name('tenant.activity.index');

        Route::prefix('settings')->as('settings.')->group(function (): void {
            Route::get('workspace', [WorkspaceController::class, 'edit'])
                ->middleware('permission:workspace.view')
                ->name('workspace.edit');
            Route::patch('workspace', [WorkspaceController::class, 'update'])
                ->middleware('permission:workspace.update')
                ->name('workspace.update');

            Route::resource('roles', RoleController::class)
                ->except('show')
                ->names('roles')
                ->middlewareFor('index', 'permission:roles.view')
                ->middlewareFor(['create', 'store'], 'permission:roles.create')
                ->middlewareFor(['edit', 'update'], 'permission:roles.edit')
                ->middlewareFor('destroy', 'permission:roles.delete');

            Route::resource('members', MemberController::class)
                ->except('show')
                ->names('members')
                ->middlewareFor('index', 'permission:users.view')
                ->middlewareFor(['create', 'store'], 'permission:users.create')
                ->middlewareFor(['edit', 'update'], 'permission:users.edit')
                ->middlewareFor('destroy', 'permission:users.delete');
        });
    });
});
