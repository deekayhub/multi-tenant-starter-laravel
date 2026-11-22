<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TenantImpersonationController extends Controller
{
    /**
     * Issue a package impersonation token for the tenant's primary Admin.
     */
    public function __invoke(Tenant $tenant): RedirectResponse
    {
        $host = $tenant->preferredHost();

        abort_if($host === null, 422, 'This workspace does not have an accessible domain.');

        $owner = $tenant->run(function (): User {
            $owner = User::query()
                ->whereHas('roles', function (Builder $query): void {
                    $query->where('name', PermissionSeeder::ADMIN_ROLE);
                })
                ->orderBy('id')
                ->first();

            if ($owner === null) {
                throw new HttpException(422, 'This workspace does not have an Admin user.');
            }

            return $owner;
        });

        $token = tenancy()->impersonate(
            $tenant,
            (string) $owner->getKey(),
            tenant_route($host, 'tenant.dashboard'),
            'web',
        );

        return redirect()->away(tenant_route($host, 'tenant.impersonate', [
            'token' => $token->token,
        ]));
    }
}
