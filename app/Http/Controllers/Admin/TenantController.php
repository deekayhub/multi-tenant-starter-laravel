<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTenantRequest;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\TenantDatabaseSeeder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class TenantController extends Controller
{
    /**
     * Display a searchable list of all tenants.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $tenants = Tenant::query()
            ->with('domains')
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhereHas('domains', function (Builder $query) use ($search): void {
                        $query->where('domain', 'like', "%{$search}%");
                    });
            })
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.tenants.index', compact('tenants', 'search'));
    }

    /**
     * Show the manual tenant provisioning form.
     */
    public function create(): View
    {
        return view('admin.tenants.create');
    }

    /**
     * Provision a tenant, its domain, and its primary Admin user.
     */
    public function store(StoreTenantRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $tenant = DB::transaction(function () use ($validated): Tenant {
            $tenant = Tenant::query()->create([
                'name' => $validated['workspace_name'],
                'email' => $validated['owner_email'],
                'status' => Tenant::STATUS_ACTIVE,
            ]);

            $tenant->domains()->create(['domain' => $validated['subdomain']]);

            $tenant->run(function () use ($tenant, $validated): void {
                (new TenantDatabaseSeeder)->run();

                $owner = User::query()->create([
                    'name' => $validated['owner_name'],
                    'email' => $validated['owner_email'],
                    'password' => Hash::make($validated['password']),
                    'tenant_id' => $tenant->getTenantKey(),
                    'email_verified_at' => now(),
                ]);

                $owner->assignRole(Role::findByName(PermissionSeeder::ADMIN_ROLE));
            });

            return $tenant;
        });

        activity('admin')
            ->causedBy($request->user())
            ->event('tenant.created')
            ->withProperties([
                'tenant_id' => $tenant->getTenantKey(),
                'tenant_name' => $tenant->name,
                'subdomain' => $validated['subdomain'],
            ])
            ->log('Created tenant '.$tenant->name);

        return redirect()->route('admin.tenants.show', $tenant)
            ->with('status', __('Workspace created successfully.'));
    }

    /**
     * Show detailed information about a tenant.
     */
    public function show(Tenant $tenant): View
    {
        $tenant->load('domains');

        $memberCount = $tenant->run(
            fn (): int => User::query()->where('tenant_id', $tenant->getTenantKey())->count(),
        );

        $recentActivity = Activity::query()
            ->whereHas('causer', function (Builder $query) use ($tenant): void {
                $query->where('tenant_id', $tenant->getTenantKey());
            })
            ->with('causer')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        return view('admin.tenants.show', compact('tenant', 'memberCount', 'recentActivity'));
    }

    /**
     * Toggle a tenant between active and suspended.
     */
    public function toggleStatus(Request $request, Tenant $tenant): RedirectResponse
    {
        $fromStatus = $tenant->status;
        $toStatus = $fromStatus === Tenant::STATUS_ACTIVE
            ? Tenant::STATUS_SUSPENDED
            : Tenant::STATUS_ACTIVE;

        $tenant->update(['status' => $toStatus]);

        activity('admin')
            ->causedBy($request->user())
            ->event('tenant.status_updated')
            ->withProperties([
                'tenant_id' => $tenant->getTenantKey(),
                'tenant_name' => $tenant->name,
                'from' => $fromStatus,
                'to' => $toStatus,
            ])
            ->log(ucfirst($toStatus).' tenant '.$tenant->name);

        return back()->with('status', __('Workspace is now :status.', ['status' => $toStatus]));
    }

    /**
     * Delete a tenant and its tenant-scoped application data.
     */
    public function destroy(Request $request, Tenant $tenant): RedirectResponse
    {
        $tenant->load('domains');

        $tenantId = $tenant->getTenantKey();
        $tenantName = $tenant->name;
        $domains = $tenant->domains->pluck('domain')->implode(', ');
        $userIds = User::query()->where('tenant_id', $tenantId)->pluck('id');

        DB::transaction(function () use ($tenant, $tenantId, $userIds): void {
            Activity::query()
                ->where(function (Builder $query) use ($tenantId, $userIds): void {
                    $query->where('properties->tenant_id', $tenantId)
                        ->orWhere(function (Builder $query) use ($userIds): void {
                            $query->where('causer_type', User::class)
                                ->whereIn('causer_id', $userIds);
                        });
                })
                ->delete();

            DB::table(config('permission.table_names.model_has_permissions'))
                ->where('model_type', User::class)
                ->whereIn(config('permission.column_names.model_morph_key'), $userIds)
                ->delete();

            DB::table(config('permission.table_names.roles'))
                ->where(config('permission.column_names.team_foreign_key'), $tenantId)
                ->delete();

            $tenant->delete();
        });

        activity('admin')
            ->causedBy($request->user())
            ->event('tenant.deleted')
            ->withProperties([
                'tenant_id' => $tenantId,
                'tenant_name' => $tenantName,
                'domains' => $domains,
            ])
            ->log('Deleted tenant '.$tenantName);

        return redirect()->route('admin.tenants.index')
            ->with('status', __('Workspace deleted successfully.'));
    }
}
