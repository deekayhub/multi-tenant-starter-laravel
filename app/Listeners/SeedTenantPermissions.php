<?php

namespace App\Listeners;

use App\Listeners\Concerns\ScopesPermissionToTenant;
use App\Models\Permission;
use App\Models\Role;
use Database\Seeders\PermissionSeeder;
use Stancl\Tenancy\Events\TenantCreated;

class SeedTenantPermissions
{
    use ScopesPermissionToTenant;

    public function handle(TenantCreated $event): void
    {
        $context = $this->capturePermissionContext();
        $this->useTenantPermissionContext((string) $event->tenant->getTenantKey());

        try {
            (new PermissionSeeder)->run();

            Role::findOrCreate(PermissionSeeder::ADMIN_ROLE)
                ->syncPermissions(Permission::query()->whereIn('name', PermissionSeeder::names())->get());
        } finally {
            $this->restorePermissionContext($context);
        }
    }
}
