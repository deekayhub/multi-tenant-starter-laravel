<x-settings-layout
    :title="__('Roles & Permissions')"
    :description="__('Group permissions into roles, then assign those roles to your team members.')"
>
    <div class="space-y-5">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="inline-flex items-center gap-2 rounded-lg border border-line bg-card px-3 py-2 text-xs font-medium text-muted-foreground shadow-sm">
                <x-icon name="cog" class="h-4 w-4" />
                <span>{{ trans_choice(':count role|:count roles', $roles->total()) }}</span>
            </div>

            @can('roles.create')
                <a href="{{ route('settings.roles.create') }}" class="btn-primary">
                    <x-icon name="plus" class="h-4 w-4" />
                    {{ __('New role') }}
                </a>
            @endcan
        </div>

        @if (session('status'))
            <div role="status" class="rounded-xl border border-success/25 bg-success/10 px-4 py-3 text-sm font-medium text-success">
                {{ session('status') }}
            </div>
        @endif

        <section class="glass-card overflow-hidden" aria-labelledby="roles-heading">
            <div class="flex flex-col gap-2 border-b border-line px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 id="roles-heading" class="text-lg font-semibold tracking-tight text-foreground">{{ __('Workspace roles') }}</h3>
                    <p class="mt-1 text-xs text-muted-foreground">{{ __('The Admin role is protected for this workspace.') }}</p>
                </div>
                <span class="text-xs text-muted-foreground">{{ __('Showing page :page', ['page' => $roles->currentPage()]) }}</span>
            </div>

            @if ($roles->isEmpty())
                <div class="flex flex-col items-center gap-3 px-5 py-16 text-center">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-muted text-muted-foreground">
                        <x-icon name="cog" class="h-7 w-7" />
                    </span>
                    <p class="text-sm font-semibold text-foreground">{{ __('No roles yet') }}</p>
                    <p class="max-w-sm text-xs leading-5 text-muted-foreground">{{ __('Create a role to start organizing workspace access.') }}</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <caption class="sr-only">{{ __('Workspace roles') }}</caption>
                        <thead>
                            <tr class="border-b border-line bg-muted/50 text-[10px] font-semibold uppercase tracking-[0.12em] text-muted-foreground">
                                <th scope="col" class="px-5 py-3 font-semibold">{{ __('Role') }}</th>
                                <th scope="col" class="px-5 py-3 font-semibold">{{ __('Permissions') }}</th>
                                <th scope="col" class="px-5 py-3 text-right font-semibold">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @foreach ($roles as $role)
                                @php
                                    $isAdmin = $role->name === \Database\Seeders\PermissionSeeder::ADMIN_ROLE;
                                    $permissionNames = $role->permissions->pluck('name')->all();
                                @endphp
                                <tr class="transition-colors hover:bg-muted/40">
                                    <td class="px-5 py-3">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="text-sm font-semibold text-foreground">{{ $role->name }}</span>
                                            @if ($isAdmin)
                                                <span class="rounded-md border border-warning/25 bg-warning/10 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.08em] text-caution">
                                                    {{ __('Protected system role') }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-3">
                                        @if ($permissionNames === [])
                                            <span class="text-xs text-muted-foreground">{{ __('No permissions') }}</span>
                                        @else
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach ($permissionNames as $permissionName)
                                                    <span class="rounded-md border border-line bg-muted/60 px-2 py-0.5 font-mono text-[10px] font-medium text-foreground">
                                                        {{ $permissionName }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="flex flex-wrap items-center justify-end gap-2">
                                            @can('roles.edit')
                                                @if ($isAdmin)
                                                    <button type="button" class="btn-quiet cursor-not-allowed opacity-50" disabled title="{{ __('The Admin role cannot be renamed.') }}">{{ __('Edit') }}</button>
                                                @else
                                                    <a href="{{ route('settings.roles.edit', $role) }}" class="btn-quiet">{{ __('Edit') }}</a>
                                                @endif
                                            @endcan
                                            @can('roles.delete')
                                                @if ($isAdmin)
                                                    <button type="button" class="btn-quiet cursor-not-allowed opacity-50" disabled title="{{ __('The Admin role cannot be deleted.') }}">{{ __('Delete') }}</button>
                                                @else
                                                    <form method="POST" action="{{ route('settings.roles.destroy', $role) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-quiet text-destructive hover:text-destructive">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-line px-5 py-4">
                    {{ $roles->links() }}
                </div>
            @endif
        </section>
    </div>
</x-settings-layout>
