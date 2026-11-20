<?php

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;

it('renders workspace details in settings', function () {
    [$tenant, $user] = rbacTenantWithAdmin('acme');

    $this->get(rbacLoginUrl($tenant, $user))->assertRedirect();

    $this->get(route('settings.workspace.edit'))
        ->assertOk()
        ->assertSee('Workspace ID')
        ->assertSee('Acme Labs')
        ->assertSee($user->email)
        ->assertSee('acme')
        ->assertSee(rbacTenantBaseUrl($tenant).'/settings/members');
});

it('updates workspace details and adds a custom domain', function () {
    [$tenant, $user] = rbacTenantWithAdmin('acme');

    $this->get(rbacLoginUrl($tenant, $user))->assertRedirect();

    $this->patch(route('settings.workspace.update'), [
        'name' => 'Acme Global',
        'email' => 'owner@acme.test',
        'custom_domain' => 'https://acme.example.com/',
    ])->assertRedirect(route('settings.workspace.edit'));

    $tenant->refresh();

    expect($tenant->name)->toBe('Acme Global');
    expect($tenant->email)->toBe('owner@acme.test');
    expect($tenant->customDomain())->toBe('acme.example.com');

    $this->assertDatabaseCount('domains', 2);
    $this->get(route('settings.workspace.edit'))->assertOk()->assertSee('acme.example.com');
});

it('removes the custom domain when the field is left blank', function () {
    [$tenant, $user] = rbacTenantWithAdmin('acme');
    $tenant->domains()->create(['domain' => 'acme.example.com']);

    $this->get(rbacLoginUrl($tenant, $user))->assertRedirect();

    $this->patch(route('settings.workspace.update'), [
        'name' => $tenant->name,
        'email' => $tenant->email,
        'custom_domain' => '',
    ])->assertRedirect(route('settings.workspace.edit'));

    $this->assertDatabaseMissing('domains', ['domain' => 'acme.example.com']);
    expect($tenant->refresh()->customDomain())->toBeNull();
});

it('rejects a custom domain below the central domain', function () {
    [$tenant, $user] = rbacTenantWithAdmin('acme');

    $this->get(rbacLoginUrl($tenant, $user))->assertRedirect();

    $this->patch(route('settings.workspace.update'), [
        'name' => $tenant->name,
        'email' => $tenant->email,
        'custom_domain' => 'acme.saas.test',
    ])->assertSessionHasErrors([
        'custom_domain' => 'Use the subdomain field for addresses under saas.test.',
    ]);
});

it('rejects a custom domain that belongs to another tenant', function () {
    [$tenant, $user] = rbacTenantWithAdmin('acme');

    $otherTenant = Tenant::create(['name' => 'Globex']);
    $otherTenant->domains()->create(['domain' => 'taken.example.com']);

    $this->get(rbacLoginUrl($tenant, $user))->assertRedirect();

    $this->patch(route('settings.workspace.update'), [
        'name' => $tenant->name,
        'email' => $tenant->email,
        'custom_domain' => 'taken.example.com',
    ])->assertSessionHasErrors('custom_domain');
});

it('hides workspace settings from users without workspace permissions', function () {
    [$tenant, $user] = rbacTenantWithAdmin('acme');

    $viewerRole = usingTenantPermissionContext($tenant, function () {
        $role = Role::create(['name' => 'Member Viewer']);
        $role->syncPermissions(['users.view']);

        return $role;
    });

    $viewer = User::factory()->create([
        'name' => 'Member Viewer',
        'tenant_id' => $tenant->getTenantKey(),
    ]);

    usingTenantPermissionContext($tenant, fn () => $viewer->assignRole($viewerRole));

    $this->get(rbacLoginUrl($tenant, $viewer))->assertRedirect();

    $this->get(route('settings.workspace.edit'))->assertForbidden();
    $this->get(route('settings.members.index'))
        ->assertOk()
        ->assertDontSee(rbacTenantBaseUrl($tenant).'/settings/workspace');
});
