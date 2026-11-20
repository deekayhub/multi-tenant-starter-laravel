<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MemberController extends Controller
{
    /**
     * Display the members of the current tenant.
     */
    public function index(): View
    {
        $members = User::query()
            ->with('roles')
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(15);

        return view('tenant.members.index', [
            'members' => $members,
        ]);
    }

    /**
     * Show the form for creating a member.
     */
    public function create(): View
    {
        return view('tenant.members.create', [
            'roles' => $this->assignableRoles(),
            'assignedRoles' => [],
        ]);
    }

    /**
     * Store a newly created member.
     */
    public function store(StoreMemberRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $member = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'tenant_id' => (string) tenant()->getTenantKey(),
        ]);

        $member->syncRoles($validated['roles'] ?? []);

        return redirect()->route('tenant.members.index')
            ->with('status', __('Member added.'));
    }

    /**
     * Show the form for editing a member.
     */
    public function edit(User $member): View
    {
        $member->loadMissing('roles');

        return view('tenant.members.edit', [
            'member' => $member,
            'roles' => $this->assignableRoles(),
            'assignedRoles' => $member->roles->pluck('id')->all(),
        ]);
    }

    /**
     * Update the given member.
     */
    public function update(UpdateMemberRequest $request, User $member): RedirectResponse
    {
        $validated = $request->validated();
        $roleIds = $validated['roles'] ?? [];

        $this->ensureLastAdminIsPreserved($member, $roleIds);

        $member->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);
        $member->syncRoles($roleIds);

        return redirect()->route('tenant.members.index')
            ->with('status', __('Member updated.'));
    }

    /**
     * Delete the given member.
     */
    public function destroy(User $member): RedirectResponse
    {
        abort_if($member->is(auth()->user()), 403, __('You cannot delete your own account.'));

        if ($member->hasRole(PermissionSeeder::ADMIN_ROLE) && $this->adminCount() <= 1) {
            abort(403, __('The tenant must keep at least one Admin.'));
        }

        $member->delete();

        return redirect()->route('tenant.members.index')
            ->with('status', __('Member removed.'));
    }

    /** @param list<int> $roleIds */
    private function ensureLastAdminIsPreserved(User $member, array $roleIds): void
    {
        if (! $member->hasRole(PermissionSeeder::ADMIN_ROLE)) {
            return;
        }

        $adminRole = Role::findByName(PermissionSeeder::ADMIN_ROLE);

        if (in_array($adminRole->getKey(), $roleIds, true)) {
            return;
        }

        if ($this->adminCount() <= 1) {
            throw ValidationException::withMessages([
                'roles' => __('The tenant must keep at least one Admin.'),
            ]);
        }
    }

    private function adminCount(): int
    {
        return User::query()->role(PermissionSeeder::ADMIN_ROLE)->count();
    }

    /** @return Collection<int, Role> */
    private function assignableRoles()
    {
        return Role::query()->withCount('permissions')->orderBy('name')->get();
    }
}
