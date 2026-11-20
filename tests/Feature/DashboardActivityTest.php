<?php

use App\Http\Controllers\Tenant\LoginController;
use App\Models\Tenant;
use App\Models\User;

it('shows recent authentication activity for the current group', function () {
    $tenant = Tenant::create(['name' => 'Acme']);
    $otherTenant = Tenant::create(['name' => 'Globex']);

    $user = User::factory()->create(['tenant_id' => $tenant->id, 'name' => 'Alice Example']);
    $otherUser = User::factory()->create(['tenant_id' => $otherTenant->id, 'name' => 'Bob Secret']);

    activity('authentication')->causedBy($user)->event('login')->log('Logged in');
    activity('authentication')->causedBy($otherUser)->event('logout')->log('Logged out');

    $this->actingAs($user)->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Signed in')
        ->assertSee('Alice Example')
        ->assertDontSee('Bob Secret');
});

it('shows an empty state when the group has no activity', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('dashboard'))
        ->assertOk()
        ->assertSee('No activity yet');
});

it('keeps the dashboard link on the current tenant domain', function () {
    $tenant = Tenant::create(['name' => 'Acme']);
    $tenant->domains()->create(['domain' => 'acme']);

    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    assignTenantAdminRole($user, $tenant);

    $token = LoginController::issueToken($user, $tenant);

    $this->get('http://acme.saas.test/login/'.$token)->assertRedirect();

    $this->get('http://acme.saas.test/activity')
        ->assertOk()
        ->assertSee('href="http://acme.saas.test"', false)
        ->assertDontSee('href="http://saas.test/dashboard"', false);
});
