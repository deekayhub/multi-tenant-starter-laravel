<x-admin-layout :title="__('Dashboard')">
    <x-slot name="header">
        <div class="admin-page-header-row flex flex-wrap items-end justify-between gap-x-6 gap-y-4">
            <div class="min-w-0">
                <p class="admin-eyebrow text-[11px] font-semibold uppercase tracking-[0.16em] text-zinc-500">{{ __('Central overview') }}</p>
                <h1 class="admin-title mt-2 text-2xl font-semibold tracking-tight text-zinc-950 sm:text-[28px]">{{ __('SaaS Dashboard') }}</h1>
                <p class="admin-subtitle mt-2 text-sm leading-6 text-zinc-500">{{ __('Monitor every workspace from one control room.') }}</p>
            </div>
            <a href="{{ route('admin.tenants.create') }}" class="admin-button btn-primary shrink-0">
                <x-icon name="plus" class="h-4 w-4" />
                {{ __('Provision Workspace') }}
            </a>
        </div>
    </x-slot>

    <section aria-label="{{ __('Workspace metrics') }}" class="admin-kpi-grid grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['label' => __('Total Tenants'), 'value' => $kpis['total'], 'icon' => 'box', 'tone' => ''],
            ['label' => __('Active Tenants'), 'value' => $kpis['active'], 'icon' => 'check', 'tone' => 'admin-kpi-icon--success'],
            ['label' => __('Suspended Tenants'), 'value' => $kpis['suspended'], 'icon' => 'alert', 'tone' => 'admin-kpi-icon--warning'],
            ['label' => __('New Tenants This Month'), 'value' => $kpis['new_this_month'], 'icon' => 'clock', 'tone' => 'admin-kpi-icon--accent'],
        ] as $metric)
            <article class="admin-kpi h-full rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <div class="admin-kpi-row flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="admin-kpi-label text-[11px] font-semibold uppercase tracking-[0.1em] text-zinc-500">{{ $metric['label'] }}</p>
                        <p class="admin-kpi-value mt-3 text-[28px] font-semibold tracking-tight text-zinc-950 tabular-nums">{{ $metric['value'] }}</p>
                    </div>
                    <span @class([
                        'admin-kpi-icon flex h-10 w-10 shrink-0 items-center justify-center rounded-lg',
                        $metric['tone'],
                    ])>
                        <x-icon :name="$metric['icon']" class="h-4 w-4" />
                    </span>
                </div>
            </article>
        @endforeach
    </section>

    <section class="admin-panel mt-8 overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
        <div class="admin-panel-header flex flex-wrap items-center justify-between gap-x-4 gap-y-2 border-b border-zinc-200 px-5 py-4">
            <div class="min-w-0">
                <h2 class="admin-panel-title text-base font-semibold tracking-tight text-zinc-950">{{ __('Recently created workspaces') }}</h2>
                <p class="admin-panel-subtitle mt-1 text-xs leading-5 text-zinc-500">{{ __('The six newest tenants in the platform.') }}</p>
            </div>
            <a href="{{ route('admin.tenants.index') }}" class="admin-link inline-flex shrink-0 items-center gap-1.5 text-sm font-semibold text-zinc-600 hover:text-zinc-900">
                {{ __('View all') }}
                <x-icon name="arrow-right" class="h-4 w-4" />
            </a>
        </div>

        @if ($recentTenants->isEmpty())
            <p class="admin-empty px-5 py-10 text-center text-sm text-zinc-500">{{ __('No workspaces have been provisioned yet.') }}</p>
        @else
            <div class="admin-table-wrap overflow-x-auto">
                <table class="admin-table w-full min-w-[44rem] text-left text-sm">
                    <thead class="bg-zinc-50 text-[11px] font-semibold uppercase tracking-[0.1em] text-zinc-500">
                        <tr>
                            <th scope="col" class="admin-th px-5 py-3">{{ __('Workspace') }}</th>
                            <th scope="col" class="admin-th px-5 py-3">{{ __('Subdomain') }}</th>
                            <th scope="col" class="admin-th px-5 py-3">{{ __('Status') }}</th>
                            <th scope="col" class="admin-th px-5 py-3">{{ __('Created') }}</th>
                            <th scope="col" class="admin-th admin-th--right px-5 py-3 text-right">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @foreach ($recentTenants as $tenant)
                            <tr class="transition-colors hover:bg-zinc-50/70">
                                <td class="admin-td px-5 py-3.5">
                                    <p class="admin-row-title font-semibold text-zinc-900">{{ $tenant->name }}</p>
                                    <p class="admin-meta admin-mono font-mono text-[11px] text-zinc-500">{{ $tenant->getTenantKey() }}</p>
                                </td>
                                <td class="admin-td admin-mono px-5 py-3.5 font-mono text-xs text-zinc-600">{{ $tenant->subdomain() ?? $tenant->customDomain() ?? '—' }}</td>
                                <td class="admin-td px-5 py-3.5"><x-admin.status-badge :status="$tenant->status" /></td>
                                <td class="admin-td admin-meta px-5 py-3.5 text-xs text-zinc-500">{{ $tenant->created_at?->format('M j, Y') }}</td>
                                <td class="admin-td admin-td--right px-5 py-3.5 text-right">
                                    <a href="{{ route('admin.tenants.show', $tenant) }}" class="admin-link text-sm font-semibold text-zinc-600 hover:text-zinc-900">{{ __('Inspect') }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</x-admin-layout>
