<?php

use App\Models\Tenant;

function tenantRegistrationPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Ada Lovelace',
        'tenant_name' => 'Globex Labs',
        'subdomain' => 'globex',
        'email' => 'ada@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ], $overrides);
}

it('creates a subdomain and redirects to it', function () {
    $response = $this->post('/register', tenantRegistrationPayload());

    $tenant = Tenant::query()->where('name', 'Globex Labs')->firstOrFail();

    $response->assertRedirectContains('/login/');

    expect(parse_url($response->headers->get('Location'), PHP_URL_HOST))->toBe('globex.saas.test');

    $this->assertDatabaseHas('domains', ['domain' => 'globex', 'tenant_id' => $tenant->id]);
    $this->assertDatabaseCount('domains', 1);
});

it('ignores a custom domain submitted during registration', function () {
    $response = $this->post('/register', tenantRegistrationPayload([
        'domain' => 'globex.example.com',
    ]));

    $tenant = Tenant::query()->where('name', 'Globex Labs')->firstOrFail();

    $response->assertRedirectContains('/login/');

    expect(parse_url($response->headers->get('Location'), PHP_URL_HOST))->toBe('globex.saas.test');

    $this->assertDatabaseHas('domains', ['domain' => 'globex', 'tenant_id' => $tenant->id]);
    $this->assertDatabaseMissing('domains', ['domain' => 'globex.example.com', 'tenant_id' => $tenant->id]);
    $this->assertDatabaseCount('domains', 1);
});

it('rejects a registration without a subdomain', function () {
    $this->post('/register', tenantRegistrationPayload([
        'subdomain' => null,
    ]))->assertSessionHasErrors([
        'subdomain' => 'Choose a subdomain.',
    ]);

    $this->assertDatabaseCount('tenants', 0);
});

it('rejects invalid subdomains', function (string $subdomain, string $message) {
    $this->post('/register', tenantRegistrationPayload([
        'subdomain' => $subdomain,
    ]))->assertSessionHasErrors(['subdomain' => $message]);

    $this->assertDatabaseCount('tenants', 0);
})->with([
    'reserved' => ['www', 'That subdomain is reserved.'],
    'underscore' => ['my_shop', 'Use lowercase letters, numbers and hyphens only.'],
    'leading hyphen' => ['-shop', 'Use lowercase letters, numbers and hyphens only.'],
]);

it('rejects a subdomain already used as a full domain', function () {
    $tenant = Tenant::create(['name' => 'Existing Tenant']);
    $tenant->domains()->create(['domain' => 'globex.saas.test']);

    $this->post('/register', tenantRegistrationPayload([
        'subdomain' => 'globex',
    ]))->assertSessionHasErrors([
        'subdomain' => 'That subdomain is already in use.',
    ]);

    $this->assertDatabaseCount('users', 0);
});
