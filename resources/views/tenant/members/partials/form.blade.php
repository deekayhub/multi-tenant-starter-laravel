@php
    $isEditing = $member !== null;
    $selectedRoleIds = array_map('strval', old('roles', $assignedRoles));
@endphp

<x-form-error-summary :errors="$errors" :id-map="['name' => 'name', 'email' => 'email', 'password' => 'password', 'password_confirmation' => 'password_confirmation', 'roles' => 'roles']" />

<form method="POST" action="{{ $action }}" class="form-grid">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="form-field">
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

    <div class="form-field">
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
        <div class="form-field">
            <label for="password" class="field-label">{{ __('Password') }}</label>
            <input
                id="password"
                name="password"
                type="password"
                autocomplete="new-password"
                class="field-input @error('password') field-input-error @enderror"
            >
            <p class="mt-1.5 text-xs text-muted-foreground">{{ __('Use at least eight characters.') }}</p>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="form-field">
            <label for="password_confirmation" class="field-label">{{ __('Confirm password') }}</label>
            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                autocomplete="new-password"
                class="field-input @error('password_confirmation') field-input-error @enderror"
            >
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>
    @endunless

    <div id="roles" class="form-field-full">
        <p class="field-label">{{ __('Roles') }}</p>
        <p class="mb-3 text-xs text-muted-foreground">{{ __('Roles determine what this member can manage in the workspace.') }}</p>

        @if ($roles->isEmpty())
            <p class="rounded-xl border border-line bg-muted/30 p-4 text-xs text-muted-foreground">{{ __('No roles are available yet.') }}</p>
        @else
            <div class="grid gap-3 sm:grid-cols-2">
                @foreach ($roles as $role)
                    @php
                        $isProtectedAdmin = $isSoleAdmin && $role->name === \Database\Seeders\PermissionSeeder::ADMIN_ROLE;
                    @endphp
                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-line bg-card p-3 shadow-sm transition-colors hover:border-zinc-300 hover:bg-muted/30">
                        @if ($isProtectedAdmin)
                            <input type="hidden" name="roles[]" value="{{ $role->getKey() }}">
                        @endif
                        <input
                            type="checkbox"
                            name="roles[]"
                            value="{{ $role->getKey() }}"
                            @checked(in_array((string) $role->getKey(), $selectedRoleIds, true))
                            @if ($isProtectedAdmin) disabled @endif
                            class="mt-0.5 h-4 w-4 rounded border-line text-primary focus:ring-primary disabled:cursor-not-allowed disabled:opacity-60"
                        >
                        <span class="min-w-0">
                            <span class="flex flex-wrap items-center gap-2">
                                <span class="block text-sm font-semibold text-foreground">{{ $role->name }}</span>
                                @if ($role->name === \Database\Seeders\PermissionSeeder::ADMIN_ROLE)
                                    <span class="rounded-md border border-warning/25 bg-warning/10 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.08em] text-caution">{{ __('Protected') }}</span>
                                @endif
                            </span>
                            <span class="mt-1 block text-[11px] leading-4 text-muted-foreground">
                                {{ __('Grants :count permissions.', ['count' => $role->permissions_count ?? 0]) }}
                            </span>
                            @if ($isProtectedAdmin)
                                <span class="mt-1 block text-[11px] font-medium text-caution">{{ __('Required while this is the last Admin.') }}</span>
                            @endif
                        </span>
                    </label>
                @endforeach
            </div>
        @endif

        <x-input-error :messages="$errors->get('roles')" />
        <x-input-error :messages="$errors->get('roles.*')" />
    </div>

    <div class="form-field-full flex flex-wrap items-center gap-3 border-t border-line pt-5">
        <button type="submit" class="btn-primary">{{ $submitLabel }}</button>
        <a href="{{ route('settings.members.index') }}" class="btn-secondary">{{ __('Cancel') }}</a>
    </div>
</form>
