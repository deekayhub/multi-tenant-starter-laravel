<x-form-error-summary :errors="$errors" :id-map="['name' => 'name', 'permissions' => 'permissions']" />

<form method="POST" action="{{ $action }}" class="form-grid" data-permission-form>
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="form-field">
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

    <div id="permissions" class="form-field-full">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="field-label mb-1">{{ __('Permissions') }}</p>
                <p class="text-xs text-muted-foreground">{{ __('Members with this role will be allowed to perform the selected actions.') }}</p>
            </div>
            <button
                type="button"
                data-toggle-all-permissions
                data-select-label="{{ __('Select All') }}"
                data-clear-label="{{ __('Deselect All') }}"
                aria-pressed="false"
                class="btn-secondary"
            >{{ __('Select All') }}</button>
        </div>

        @if ($permissionGroups !== [])
            <div class="mt-4 overflow-x-auto rounded-xl border border-line">
                <table class="min-w-full text-left text-sm">
                    <caption class="sr-only">{{ __('Permission matrix') }}</caption>
                    <thead>
                        <tr class="border-b border-line bg-muted/40 text-[10px] font-bold uppercase tracking-[0.12em] text-muted-foreground">
                            <th scope="col" class="px-4 py-3 font-bold">{{ __('Permission') }}</th>
                            <th scope="col" class="w-24 px-4 py-3 text-right font-bold">{{ __('Access') }}</th>
                        </tr>
                    </thead>
                    @foreach ($permissionGroups as $group)
                        <tbody class="border-b border-line last:border-b-0">
                            <tr class="bg-muted/30">
                                <th colspan="2" scope="colgroup" class="px-4 py-2.5">
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <span class="text-[11px] font-extrabold uppercase tracking-[0.12em] text-foreground">{{ $group['label'] }}</span>
                                        <button
                                            type="button"
                                            data-toggle-permission-group="{{ $group['key'] }}"
                                            data-select-label="{{ __('Select All') }}"
                                            data-clear-label="{{ __('Deselect All') }}"
                                            aria-pressed="false"
                                            class="rounded text-[11px] font-bold text-primary transition-colors hover:text-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                                        >{{ __('Select All') }}</button>
                                    </div>
                                </th>
                            </tr>
                            @foreach ($group['permissions'] as $permission)
                                <tr class="transition-colors hover:bg-white/60">
                                    <td class="px-4 py-3">
                                        <label class="flex cursor-pointer items-start gap-3">
                                            <input
                                                type="checkbox"
                                                name="permissions[]"
                                                value="{{ $permission->name }}"
                                                data-permission-checkbox
                                                data-permission-group="{{ $group['key'] }}"
                                                @checked(in_array($permission->name, old('permissions', $assignedPermissions), true))
                                                class="mt-0.5 h-4 w-4 rounded border-line text-primary focus:ring-primary"
                                            >
                                            <span class="min-w-0">
                                                <span class="block font-mono text-xs font-semibold text-foreground">{{ $permission->name }}</span>
                                                <span class="mt-0.5 block text-[11px] text-muted-foreground">{{ \Illuminate\Support\Str::headline(\Illuminate\Support\Str::after($permission->name, '.')) }}</span>
                                            </span>
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-right text-[11px] font-semibold text-muted-foreground">{{ __('Allow') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    @endforeach
                </table>
            </div>
        @else
            <p class="mt-4 rounded-xl border border-line bg-muted/30 p-4 text-xs text-muted-foreground">{{ __('No permissions are available yet.') }}</p>
        @endif

        <x-input-error :messages="$errors->get('permissions')" />
        <x-input-error :messages="$errors->get('permissions.*')" />
    </div>

    <div class="form-field-full flex flex-wrap items-center gap-3 border-t border-line pt-4">
        <button type="submit" class="btn-primary">{{ $submitLabel }}</button>
        <a href="{{ route('settings.roles.index') }}" class="btn-secondary">{{ __('Cancel') }}</a>
    </div>
</form>

@pushOnce('scripts')
    <script>
        (() => {
            const allButton = document.querySelector('[data-toggle-all-permissions]');

            if (! allButton) {
                return;
            }

            const permissionBoxes = () => Array.from(document.querySelectorAll('[data-permission-checkbox]'));

            const setChecked = (boxes, checked) => {
                boxes.forEach((box) => {
                    box.checked = checked;
                });
            };

            const syncButtons = () => {
                const boxes = permissionBoxes();
                const allChecked = boxes.length > 0 && boxes.every((box) => box.checked);

                allButton.textContent = allChecked ? allButton.dataset.clearLabel : allButton.dataset.selectLabel;
                allButton.setAttribute('aria-pressed', String(allChecked));

                document.querySelectorAll('[data-toggle-permission-group]').forEach((button) => {
                    const groupBoxes = boxes.filter((box) => box.dataset.permissionGroup === button.dataset.togglePermissionGroup);
                    const groupChecked = groupBoxes.length > 0 && groupBoxes.every((box) => box.checked);

                    button.textContent = groupChecked ? button.dataset.clearLabel : button.dataset.selectLabel;
                    button.setAttribute('aria-pressed', String(groupChecked));
                });
            };

            document.addEventListener('click', (event) => {
                const toggleAll = event.target.closest('[data-toggle-all-permissions]');

                if (toggleAll) {
                    const boxes = permissionBoxes();
                    setChecked(boxes, !boxes.every((box) => box.checked));
                    syncButtons();

                    return;
                }

                const toggleGroup = event.target.closest('[data-toggle-permission-group]');

                if (toggleGroup) {
                    const boxes = permissionBoxes().filter((box) => box.dataset.permissionGroup === toggleGroup.dataset.togglePermissionGroup);
                    setChecked(boxes, !boxes.every((box) => box.checked));
                    syncButtons();
                }
            });

            document.addEventListener('change', (event) => {
                if (event.target.matches('[data-permission-checkbox]')) {
                    syncButtons();
                }
            });

            syncButtons();
        })();
    </script>
@endpushOnce
