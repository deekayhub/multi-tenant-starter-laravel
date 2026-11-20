<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Database\Seeders\PermissionSeeder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RoleController extends Controller
{
    /**
     * Display the roles available in the current tenant.
     */
    public function index(): View
    {
        $roles = Role::query()
            ->with('permissions')
            ->orderBy('name')
            ->paginate(15);

        return view('tenant.roles.index', [
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a role.
     */
    public function create(): View
    {
        return view('tenant.roles.create', $this->permissionFormData());
    }

    /**
     * Store a newly created role.
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $role = Role::create(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        $this->logPermissionChanges($role, [], collect($validated['permissions'])->sort()->values()->all(), 'permissions_attached');

        return redirect()->route('settings.roles.index')
            ->with('status', __('Role created.'));
    }

    /**
     * Show the form for editing a role.
     */
    public function edit(Role $role): View
    {
        $this->ensureEditable($role);

        $role->loadMissing('permissions');

        return view('tenant.roles.edit', array_merge($this->permissionFormData(), [
            'role' => $role,
            'assignedPermissions' => $role->permissions->pluck('name')->all(),
        ]));
    }

    /**
     * Update the given role.
     */
    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $this->ensureEditable($role);

        $validated = $request->validated();

        $role->loadMissing('permissions');
        $previousPermissions = $role->permissions->pluck('name')->sort()->values()->all();

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        $this->logPermissionChanges($role, $previousPermissions, collect($validated['permissions'])->sort()->values()->all(), 'permissions_updated');

        return redirect()->route('settings.roles.index')
            ->with('status', __('Role updated.'));
    }

    /**
     * Delete the given role.
     */
    public function destroy(Role $role): RedirectResponse
    {
        $this->ensureEditable($role);

        $role->delete();

        return redirect()->route('settings.roles.index')
            ->with('status', __('Role deleted.'));
    }

    private function ensureEditable(Role $role): void
    {
        abort_if($role->name === PermissionSeeder::ADMIN_ROLE, 403, __('The Admin role cannot be modified.'));
    }

    /**
     * @param  list<string>  $previousPermissions
     * @param  list<string>  $newPermissions
     */
    private function logPermissionChanges(Role $role, array $previousPermissions, array $newPermissions, string $event): void
    {
        if ($previousPermissions === $newPermissions) {
            return;
        }

        activity('roles')
            ->performedOn($role)
            ->causedBy(auth()->user())
            ->event($event)
            ->withProperties([
                'old' => ['permissions' => $previousPermissions],
                'attributes' => ['permissions' => $newPermissions],
            ])
            ->log($event);
    }

    /** @return array<string, mixed> */
    private function permissionFormData(): array
    {
        $groups = Permission::query()
            ->orderBy('name')
            ->get()
            ->groupBy(fn (Permission $permission): string => Str::before($permission->name, '.'))
            ->map(fn ($permissions, string $prefix): array => [
                'key' => $prefix,
                'label' => Str::headline($prefix),
                'permissions' => $permissions->values()->all(),
            ])
            ->all();

        return [
            'permissionGroups' => $groups,
            'assignedPermissions' => [],
        ];
    }
}
