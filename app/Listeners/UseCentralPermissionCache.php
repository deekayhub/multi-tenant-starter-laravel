<?php

namespace App\Listeners;

use App\Listeners\Concerns\ScopesPermissionToTenant;
use Stancl\Tenancy\Events\TenancyEnded;

class UseCentralPermissionCache
{
    use ScopesPermissionToTenant;

    public function handle(TenancyEnded $event): void
    {
        $this->useCentralPermissionContext();
    }
}
