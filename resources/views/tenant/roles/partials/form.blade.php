<x-form-error-summary :errors="$errors" :id-map="['name' => 'name', 'permissions' => 'permissions']" />

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div>
        <label for="name" class="field-label">{{ __('Role name') }}</label>
        <input
            id="name"
            name="name"
            type="text"
            value="{{ old('name', $role?->name) }}"
            autocomplete="off"
            class="field-input @error('name') field-input-error @enderror"
            aria-describedby="name-help"
        >
        <p id="name-help" class="mt-2 text-xs text-muted-foreground">{{ __('Use a short, recognizable name such as Manager or Billing Admin.') }}</p>
        <x-input-error :messages="$errors->get('name')" />
    </div>

    <div id="permissions">
        <p class="field-label">{{ __('Permissions') }}</p>
        <p class="mb-4 text-xs text-muted-foreground">{{ __('Members with this role will be allowed to perform the selected actions.') }}</p>

        <div class="space-y-5">
            @foreach ($permissionGroups as $group)
                <fieldset class="rounded-xl border border-line bg-muted/30 p-4">
                    <legend class="px-1 text-[10px] font-bold uppercase tracking-[0.12em] text-muted-foreground">{{ $group['label'] }}</legend>
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach ($group['permissions'] as $permission)
                            <label class="flex cursor-pointer items-start gap-3 rounded-lg px-2 py-1.5 transition-colors hover:bg-white/70">
                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission->name }}"
                                    @checked(in_array($permission->name, old('permissions', $assignedPermissions), true))
                                    class="mt-0.5 h-4 w-4 rounded border-line text-primary focus:ring-primary"
                                >
                                <span class="min-w-0">
                                    <span class="block font-mono text-xs font-semibold text-foreground">{{ $permission->name }}</span>
                                    <span class="mt-0.5 block text-[11px] text-muted-foreground">{{ __('Allows :action', ['action' => str_replace(['.', '-'], ' ', $permission->name)]) }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>
            @endforeach
        </div>

        <x-input-error :messages="$errors->get('permissions')" />
        <x-input-error :messages="$errors->get('permissions.*')" />
    </div>

    <div class="flex flex-wrap items-center gap-3 border-t border-line pt-5">
        <button type="submit" class="btn-primary">{{ $submitLabel }}</button>
        <a href="{{ route('tenant.roles.index') }}" class="btn-secondary">{{ __('Cancel') }}</a>
    </div>
</form>
