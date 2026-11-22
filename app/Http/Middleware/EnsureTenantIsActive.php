<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = tenant();

        if ($tenant === null) {
            return $next($request);
        }

        // Status may change between requests while tenancy stays initialized in
        // long-running processes, so always check the persisted value.
        $currentTenant = Tenant::query()->find($tenant->getTenantKey());

        if ($currentTenant === null || $currentTenant->status === Tenant::STATUS_ACTIVE) {
            return $next($request);
        }

        return response()->view('tenant.suspended', [
            'tenant' => $currentTenant,
        ], 403);
    }
}
