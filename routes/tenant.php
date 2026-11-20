<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Tenant\LoginController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    PreventAccessFromCentralDomains::class,
    InitializeTenancyByDomainOrSubdomain::class,
])->group(function (): void {
    Route::get('/login/{token}', LoginController::class)
        ->middleware('throttle:6,1')
        ->name('tenant.login');

    Route::get('/', [DashboardController::class, 'index'])
        ->middleware('auth')
        ->name('tenant.dashboard');

    Route::middleware('auth')->group(function (): void {
        Route::get('/activity', [ActivityController::class, 'index'])
            ->middleware('permission:activity-logs.view')
            ->name('tenant.activity.index');

        Route::resource('roles', RoleController::class)
            ->except('show')
            ->names('tenant.roles')
            ->middlewareFor('index', 'permission:roles.view')
            ->middlewareFor(['create', 'store'], 'permission:roles.create')
            ->middlewareFor(['edit', 'update'], 'permission:roles.edit')
            ->middlewareFor('destroy', 'permission:roles.delete');

        Route::resource('members', MemberController::class)
            ->except('show')
            ->names('tenant.members')
            ->middlewareFor('index', 'permission:users.view')
            ->middlewareFor(['create', 'store'], 'permission:users.create')
            ->middlewareFor(['edit', 'update'], 'permission:users.edit')
            ->middlewareFor('destroy', 'permission:users.delete');
    });
});
