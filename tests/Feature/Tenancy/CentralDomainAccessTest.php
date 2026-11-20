<?php

it('serves the central app on development hosts', function (string $host) {
    $this->get('http://'.$host.'/')->assertOk();
})->with(['127.0.0.1', 'localhost']);

it('blocks tenant routes on development hosts', function () {
    $this->get('http://127.0.0.1/login/token')->assertNotFound();
});

it('generates central URLs using the canonical domain', function () {
    expect(route('register'))->toBe('http://saas.test/register');
});
