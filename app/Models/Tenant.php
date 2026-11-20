<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Database\Concerns\HasDomains;
// use Stancl\Tenancy\Database\Concerns\HasDatabase;

class Tenant extends BaseTenant
{
    use HasDomains;
    // use HasDatabase; // DON'T USE THIS IN SINGLE DB MODE

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'data'
        ];
    }

    /**
     * For single DB, we store everything in central DB
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'data',
    ];
}