<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Seed application data that is scoped to each tenant.
     */
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
    }
}
