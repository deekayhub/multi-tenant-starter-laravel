<?php

namespace App\Models;

use App\Models\Scopes\TenantRoleScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Permission\Models\Role as SpatieRole;

#[ScopedBy([TenantRoleScope::class])]
class Role extends SpatieRole
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('roles')
            ->logOnly(['name', 'guard_name', 'tenant_id'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }
}
