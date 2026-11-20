<?php

namespace App\Listeners;

use App\Listeners\Concerns\ScopesPermissionToTenant;
use Stancl\Tenancy\Events\TenancyBootstrapped;

class UseTenantPermissionCache
{
    use ScopesPermissionToTenant;

    public function handle(TenancyBootstrapped $event): void
    {
        $this->useTenantPermissionContext((string) $event->tenancy->tenant->getTenantKey());
    }
}
