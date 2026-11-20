@php
    $isEditing = $member !== null;
    $selectedRoleIds = array_map('strval', old('roles', $assignedRoles));
@endphp

<x-form-error-summary :errors="$errors" :id-map="['name' => 'name', 'email' => 'email', 'password' => 'password', 'roles' => 'roles']" />

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div>
        <label for="name" class="field-label">{{ __('Name') }}</label>
        <input
            id="name"
            name="name"
            type="text"
            value="{{ old('name', $member?->name) }}"
            autocomplete="name"
            class="field-input @error('name') field-input-error @enderror"
        >
        <x-input-error :messages="$errors->get('name')" />
    </div>

    <div>
        <label for="email" class="field-label">{{ __('Email address') }}</label>
        <input
            id="email"
            name="email"
            type="email"
            value="{{ old('email', $member?->email) }}"
            autocomplete="email"
            class="field-input @error('email') field-input-error @enderror"
        >
        <x-input-error :messages="$errors->get('email')" />
    </div>

    @unless ($isEditing)
        <div>
            <label for="password" class="field-label">{{ __('Password') }}</label>
            <input
                id="password"
                name="password"
                type="password"
                autocomplete="new-password"
                class="field-input @error('password') field-input-error @enderror"
            >
            <p class="mt-2 text-xs text-muted-foreground">{{ __('Use at least eight characters.') }}</p>
            <x-input-error :messages="$errors->get('password')" />
        </div>
    @endunless

    <div id="roles">
        <p class="field-label">{{ __('Roles') }}</p>
        <p class="mb-4 text-xs text-muted-foreground">{{ __('Roles determine what this member can manage in the workspace.') }}</p>

        @if ($roles->isEmpty())
            <p class="rounded-xl border border-line bg-muted/30 p-4 text-xs text-muted-foreground">{{ __('No roles are available yet.') }}</p>
        @else
            <div class="grid gap-3 sm:grid-cols-2">
                @foreach ($roles as $role)
                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-line bg-muted/30 px-3 py-3 transition-colors hover:bg-white/70">
                        <input
                            type="checkbox"
                            name="roles[]"
                            value="{{ $role->getKey() }}"
                            @checked(in_array((string) $role->getKey(), $selectedRoleIds, true))
                            class="mt-0.5 h-4 w-4 rounded border-line text-primary focus:ring-primary"
                        >
                        <span class="min-w-0">
                            <span class="flex flex-wrap items-center gap-2">
                                <span class="block text-xs font-bold text-foreground">{{ $role->name }}</span>
                                @if ($role->name === \Database\Seeders\PermissionSeeder::ADMIN_ROLE)
                                    <span class="rounded-md border border-warning/25 bg-warning/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.08em] text-caution">{{ __('Protected') }}</span>
                                @endif
                            </span>
                            <span class="mt-1 block text-[11px] leading-4 text-muted-foreground">
                                {{ __('Grants :count permissions.', ['count' => $role->permissions_count ?? 0]) }}
                            </span>
                        </span>
                    </label>
                @endforeach
            </div>
        @endif

        <x-input-error :messages="$errors->get('roles')" />
        <x-input-error :messages="$errors->get('roles.*')" />
    </div>

    <div class="flex flex-wrap items-center gap-3 border-t border-line pt-5">
        <button type="submit" class="btn-primary">{{ $submitLabel }}</button>
        <a href="{{ route('tenant.members.index') }}" class="btn-secondary">{{ __('Cancel') }}</a>
    </div>
</form>
