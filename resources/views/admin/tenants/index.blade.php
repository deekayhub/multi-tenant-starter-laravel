<x-admin-layout :title="__('Tenants')">
    <x-slot name="header">
        <div class="admin-page-header-row flex flex-wrap items-end justify-between gap-x-6 gap-y-4">
            <div class="min-w-0">
                <p class="admin-eyebrow text-[11px] font-semibold uppercase tracking-[0.16em] text-zinc-500">{{ __('Workspace registry') }}</p>
                <h1 class="admin-title mt-2 text-2xl font-semibold tracking-tight text-zinc-950 sm:text-[28px]">{{ __('Tenants') }}</h1>
                <p class="admin-subtitle mt-2 text-sm leading-6 text-zinc-500">{{ __('Search, inspect, suspend, and provision customer workspaces.') }}</p>
            </div>
            <a href="{{ route('admin.tenants.create') }}" class="admin-button btn-primary shrink-0">
                <x-icon name="plus" class="h-4 w-4" />
                {{ __('New Tenant') }}
            </a>
        </div>
    </x-slot>

    <section class="admin-panel overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
        <form method="GET" action="{{ route('admin.tenants.index') }}" class="admin-search flex flex-col gap-3 border-b border-zinc-200 p-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="admin-search-field relative min-w-0 flex-1">
                <x-icon name="search" class="admin-search-icon pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-400" />
                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="{{ __('Search by name, ID, email, or domain') }}"
                    aria-label="{{ __('Search tenants') }}"
                    class="admin-input field-input pl-9"
                >
            </div>
            <div class="admin-search-actions flex shrink-0 gap-2">
                <button type="submit" class="admin-button admin-button--secondary btn-secondary">{{ __('Search') }}</button>
                @if ($search !== '')
                    <a href="{{ route('admin.tenants.index') }}" class="admin-button admin-button--quiet btn-quiet">{{ __('Clear') }}</a>
                @endif
            </div>
        </form>

        @if ($tenants->isEmpty())
            <p class="admin-empty px-5 py-12 text-center text-sm text-zinc-500">{{ __('No tenants match your search.') }}</p>
        @else
            <div class="admin-table-wrap overflow-x-auto">
                <table class="admin-table admin-table--wide w-full min-w-[60rem] text-left text-sm">
                    <thead class="bg-zinc-50 text-[11px] font-semibold uppercase tracking-[0.1em] text-zinc-500">
                        <tr>
                            <th scope="col" class="admin-th px-5 py-3">{{ __('Workspace / ID') }}</th>
                            <th scope="col" class="admin-th px-5 py-3">{{ __('Subdomain') }}</th>
                            <th scope="col" class="admin-th px-5 py-3">{{ __('Status') }}</th>
                            <th scope="col" class="admin-th px-5 py-3">{{ __('Created') }}</th>
                            <th scope="col" class="admin-th admin-th--right px-5 py-3 text-right">{{ __('Quick actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @foreach ($tenants as $tenant)
                            @php
                                $host = $tenant->preferredHost();
                            @endphp
                            <tr class="transition-colors hover:bg-zinc-50/70">
                                <td class="admin-td px-5 py-4">
                                    <a href="{{ route('admin.tenants.show', $tenant) }}" class="admin-row-title hover:text-zinc-700">{{ $tenant->name }}</a>
                                    <p class="admin-meta admin-mono mt-0.5 font-mono text-[11px] text-zinc-500">{{ $tenant->getTenantKey() }}</p>
                                </td>
                                <td class="admin-td px-5 py-4">
                                    @if ($host)
                                        <a href="{{ tenant_route($host, 'tenant.dashboard') }}" class="admin-link admin-mono inline-flex items-center gap-1 font-mono text-xs text-zinc-700 hover:text-zinc-800">
                                            {{ $tenant->subdomain() ?? $host }}
                                            <x-icon name="arrow-right" class="h-3.5 w-3.5" />
                                        </a>
                                    @else
                                        <span class="admin-text-muted">—</span>
                                    @endif
                                </td>
                                <td class="admin-td px-5 py-4"><x-admin.status-badge :status="$tenant->status" /></td>
                                <td class="admin-td admin-meta px-5 py-4 text-xs text-zinc-500">{{ $tenant->created_at?->format('M j, Y') }}</td>
                                <td class="admin-td admin-td--right px-5 py-4">
                                    <div class="admin-action-group flex flex-wrap items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.tenants.show', $tenant) }}" class="admin-action rounded-md px-2.5 py-1.5">{{ __('View') }}</a>

                                        @if ($host)
                                            <a href="{{ route('admin.tenants.impersonate', $tenant) }}" class="admin-action rounded-md px-2.5 py-1.5">{{ __('Login as Workspace') }}</a>
                                        @endif

                                        <form method="POST" action="{{ route('admin.tenants.toggle-status', $tenant) }}" onsubmit="return confirm('{{ $tenant->status === \App\Models\Tenant::STATUS_ACTIVE ? __('Suspend this workspace?') : __('Reactivate this workspace?') }}')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="admin-action admin-action--warning rounded-md px-2.5 py-1.5">
                                                {{ $tenant->status === \App\Models\Tenant::STATUS_ACTIVE ? __('Suspend') : __('Reactivate') }}
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.tenants.destroy', $tenant) }}" onsubmit="return confirm('{{ __('Delete this workspace and all of its tenant data? This cannot be undone.') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-action admin-action--danger rounded-md px-2.5 py-1.5">{{ __('Delete') }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="admin-pagination border-t border-zinc-200 px-5 py-4">
                {{ $tenants->links() }}
            </div>
        @endif
    </section>
</x-admin-layout>
