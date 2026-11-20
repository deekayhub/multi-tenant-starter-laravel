<?php

namespace App\Models;

use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    // Single-database tenancy: no HasDatabase trait.
    use HasDomains;

    /**
     * @return list<string>
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'data',
        ];
    }
}
