<?php

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

    Route::get('/', function () {
        return view('dashboard');
    })->middleware('auth')->name('tenant.dashboard');
});
