<?php

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

it('reuses eager loaded domains when resolving tenant hosts', function () {
    foreach (range(1, 3) as $number) {
        $tenant = Tenant::create(['name' => 'Tenant '.$number]);
        $tenant->domains()->create(['domain' => 'tenant'.$number]);
    }

    $tenants = Tenant::query()->with('domains')->orderBy('id')->get();

    DB::flushQueryLog();
    DB::enableQueryLog();

    $hosts = $tenants->map->preferredHost()->all();
    sort($hosts);

    $domainQueries = collect(DB::getQueryLog())
        ->filter(fn (array $query): bool => str_contains($query['query'], 'from `domains`'));

    expect($hosts)->toBe([
        'tenant1.'.config('tenancy.central_domains')[0],
        'tenant2.'.config('tenancy.central_domains')[0],
        'tenant3.'.config('tenancy.central_domains')[0],
    ])->and($domainQueries)->toHaveCount(0);

    DB::disableQueryLog();
});
