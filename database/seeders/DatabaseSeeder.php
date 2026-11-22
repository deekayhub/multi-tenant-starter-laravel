<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::query()->updateOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'SaaS Super Admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_super_admin' => true,
        ]);

        $this->call(TenantSeeder::class);
    }
}
