<?php

use App\Models\Tenant;
use App\Models\User;

it('renders tenant navigation with accessible submenu controls', function () {
    $tenant = Tenant::create(['name' => 'Acme']);
    $tenant->domains()->create(['domain' => 'acme']);

    $user = User::factory()->create([
        'name' => 'Alice Example',
        'tenant_id' => $tenant->getTenantKey(),
    ]);

    $this->actingAs($user)->get('http://acme.saas.test/')
        ->assertOk()
        ->assertSee('Tenant Dashboard')
        ->assertSee('aria-controls="app-sidebar-rail"', false)
        ->assertSee('id="settings-submenu-rail-trigger"', false)
        ->assertSee('aria-controls="settings-submenu-rail"', false)
        ->assertSee('id="settings-submenu-rail"', false)
        ->assertSee('aria-current="page"', false)
        ->assertSee('app-sidebar-active', false);
});

it('renders superadmin navigation with header logout and without sidebar account actions', function () {
    $user = User::factory()->create([
        'name' => 'Ada Lovelace',
        'email' => 'admin@example.com',
        'is_super_admin' => true,
    ]);

    $this->actingAs($user)->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Ada Lovelace')
        ->assertDontSee('admin@example.com')
        ->assertSee('aria-controls="admin-sidebar-rail"', false)
        ->assertSee('aria-label="Log out"', false);
});

it('marks the central profile page active in superadmin navigation', function () {
    $user = User::factory()->create([
        'name' => 'Ada Lovelace',
        'is_super_admin' => true,
    ]);

    $profileUrl = route('central.profile.edit');

    $this->actingAs($user)->get($profileUrl)
        ->assertOk()
        ->assertSee('admin-shell', false)
        ->assertSee($profileUrl, true)
        ->assertSee('aria-current="page"', false)
        ->assertSee('is-active', false)
        ->assertSee('Profile information');
});
