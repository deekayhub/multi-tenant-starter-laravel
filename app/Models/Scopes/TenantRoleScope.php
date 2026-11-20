<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantRoleScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $tenantKey = (string) config('permission.column_names.team_foreign_key');
        $column = $model->qualifyColumn($tenantKey);
        $teamId = getPermissionsTeamId();

        if ($teamId === null) {
            $builder->whereNull($column);

            return;
        }

        $builder->where($column, $teamId);
    }
}
