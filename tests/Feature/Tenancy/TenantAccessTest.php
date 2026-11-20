<?php

use App\Http\Controllers\Tenant\LoginController;
use App\Models\Tenant;
use App\Models\User;

function tenantWithDomain(string $name, string $domain): Tenant
{
    $tenant = Tenant::create(['name' => $name]);
    $tenant->domains()->create(['domain' => $domain]);

    return $tenant;
}

it('authenticates a user on the tenant domain with a signed token', function () {
    $tenant = tenantWithDomain('Acme', 'acme');
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $token = LoginController::issueToken($user, $tenant);

    $response = $this->get('http://acme.saas.test/login/'.$token);

    $response->assertRedirect(route('tenant.dashboard', absolute: false));

    $this->assertAuthenticatedAs($user);
});

it('rejects a signed token issued for another tenant', function () {
    $tenant = tenantWithDomain('Acme', 'acme');
    tenantWithDomain('Globex', 'globex');

    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $token = LoginController::issueToken($user, $tenant);

    $this->get('http://globex.saas.test/login/'.$token)->assertNotFound();

    $this->assertGuest();
});

it('rejects a tampered signed token', function () {
    $tenant = tenantWithDomain('Acme', 'acme');
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $token = LoginController::issueToken($user, $tenant);

    $tamperedToken = substr($token, 0, -1).($token[strlen($token) - 1] === 'A' ? 'B' : 'A');

    $this->get('http://acme.saas.test/login/'.$tamperedToken)->assertNotFound();

    $this->assertGuest();
});

it('authenticates and renders the dashboard on a custom domain', function () {
    $tenant = tenantWithDomain('Acme', 'acme.example.com');
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $token = LoginController::issueToken($user, $tenant);

    $this->get('http://acme.example.com/login/'.$token)
        ->assertRedirect(route('tenant.dashboard', absolute: false));

    $this->assertAuthenticatedAs($user);

    $this->get('http://acme.example.com/')->assertOk();
});

it('redirects guests to the central login page from a tenant domain', function () {
    tenantWithDomain('Acme', 'acme');

    $this->get('http://acme.saas.test/')
        ->assertRedirect('http://saas.test/login');
});
