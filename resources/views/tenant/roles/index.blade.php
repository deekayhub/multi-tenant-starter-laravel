<x-app-layout :title="__('Roles')">
    <div class="space-y-7">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.12em] text-muted-foreground">
                    <x-icon name="cog" class="h-4 w-4" />
                    {{ __('Access control') }}
                </p>
                <h1 class="mt-3 text-4xl font-extrabold leading-tight tracking-tight text-foreground sm:text-5xl">{{ __('Roles') }}</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-muted-foreground">
                    {{ __('Group permissions into roles, then assign those roles to your team members.') }}
                </p>
            </div>

            @can('roles.create')
                <a href="{{ route('tenant.roles.create') }}" class="btn-primary self-start">
                    <x-icon name="plus" class="h-4 w-4" />
                    {{ __('New role') }}
                </a>
            @endcan
        </div>

        @if (session('status'))
            <div role="status" class="rounded-xl border border-success/25 bg-success/10 px-4 py-3 text-sm font-semibold text-success">
                {{ session('status') }}
            </div>
        @endif

        <section class="glass-card overflow-hidden" aria-labelledby="roles-heading">
            <div class="flex flex-col gap-2 border-b border-line px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 id="roles-heading" class="text-xl font-extrabold tracking-tight text-foreground">{{ __('Workspace roles') }}</h2>
                    <p class="mt-1 text-xs text-muted-foreground">{{ __('The Admin role is protected for this workspace.') }}</p>
                </div>
                <span class="text-xs font-semibold text-muted-foreground">{{ trans_choice(':count role|:count roles', $roles->total()) }}</span>
            </div>

            @if ($roles->isEmpty())
                <div class="flex flex-col items-center gap-3 px-5 py-16 text-center">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                        <x-icon name="cog" class="h-7 w-7" />
                    </span>
                    <p class="text-sm font-bold text-foreground">{{ __('No roles yet') }}</p>
                    <p class="max-w-sm text-xs leading-5 text-muted-foreground">{{ __('Create a role to start organizing workspace access.') }}</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <caption class="sr-only">{{ __('Workspace roles') }}</caption>
                        <thead>
                            <tr class="text-[10px] font-bold uppercase tracking-[0.12em] text-muted-foreground">
                                <th scope="col" class="px-5 py-2.5 font-bold">{{ __('Role') }}</th>
                                <th scope="col" class="px-5 py-2.5 font-bold">{{ __('Permissions') }}</th>
                                <th scope="col" class="px-5 py-2.5 text-right font-bold">{{ __('Actions') }}</th>
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
                                            <span class="text-xs font-bold text-foreground">{{ $role->name }}</span>
                                            @if ($isAdmin)
                                                <span class="rounded-md border border-warning/25 bg-warning/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.08em] text-caution">
                                                    {{ __('Protected') }}
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
                                                    <span class="rounded-md border border-line bg-muted/60 px-2 py-0.5 font-mono text-[10px] font-semibold text-muted-foreground">
                                                        {{ $permissionName }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="flex flex-wrap items-center justify-end gap-2">
                                            @unless ($isAdmin)
                                                @can('roles.edit')
                                                    <a href="{{ route('tenant.roles.edit', $role) }}" class="btn-quiet">{{ __('Edit') }}</a>
                                                @endcan
                                                @can('roles.delete')
                                                    <form method="POST" action="{{ route('tenant.roles.destroy', $role) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-quiet text-destructive hover:text-destructive">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                @endcan
                                            @else
                                                <span class="text-xs font-medium text-muted-foreground">{{ __('Managed by the system') }}</span>
                                            @endunless
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
</x-app-layout>
