<?php

namespace App\Listeners\Concerns;

use Spatie\Permission\PermissionRegistrar;

trait ScopesPermissionToTenant
{
    /**
     * @return array{0: int|string|null, 1: string}
     */
    private function capturePermissionContext(): array
    {
        $registrar = app(PermissionRegistrar::class);

        return [$registrar->getPermissionsTeamId(), $registrar->cacheKey];
    }

    private function useTenantPermissionContext(int|string $tenantKey): void
    {
        $registrar = app(PermissionRegistrar::class);
        $registrar->setPermissionsTeamId($tenantKey);
        $registrar->initializeCache();
        $registrar->cacheKey = $this->tenantPermissionCacheKey($tenantKey);
    }

    private function useCentralPermissionContext(): void
    {
        $registrar = app(PermissionRegistrar::class);
        $registrar->setPermissionsTeamId(null);
        $registrar->initializeCache();
    }

    /**
     * @param  array{0: int|string|null, 1: string}  $context
     */
    private function restorePermissionContext(array $context): void
    {
        $registrar = app(PermissionRegistrar::class);
        $registrar->setPermissionsTeamId($context[0]);
        $registrar->initializeCache();
        $registrar->cacheKey = $context[1];
    }

    private function tenantPermissionCacheKey(int|string $tenantKey): string
    {
        return (string) config('permission.cache.key').'.tenant.'.$tenantKey;
    }
}
