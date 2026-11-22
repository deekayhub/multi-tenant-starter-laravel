<x-admin-layout :title="__('Dashboard')">
    <x-slot name="header">
        <div class="admin-page-header-row flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="admin-eyebrow text-xs font-bold uppercase tracking-[0.18em] text-cyan-700">{{ __('Central overview') }}</p>
                <h1 class="admin-title mt-2 text-2xl font-extrabold tracking-tight text-slate-950 sm:text-3xl">{{ __('SaaS Dashboard') }}</h1>
                <p class="admin-subtitle mt-1.5 text-sm text-slate-500">{{ __('Monitor every workspace from one control room.') }}</p>
            </div>
            <a href="{{ route('admin.tenants.create') }}" class="admin-button btn-primary">
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
            <article class="admin-kpi rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="admin-kpi-row flex items-start justify-between gap-4">
                    <div>
                        <p class="admin-kpi-label text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500">{{ $metric['label'] }}</p>
                        <p class="admin-kpi-value mt-3 text-3xl font-extrabold tracking-tight text-slate-950">{{ $metric['value'] }}</p>
                    </div>
                    <span @class([
                        'admin-kpi-icon flex h-9 w-9 shrink-0 items-center justify-center rounded-lg',
                        $metric['tone'],
                    ])>
                        <x-icon :name="$metric['icon']" class="h-4 w-4" />
                    </span>
                </div>
            </article>
        @endforeach
    </section>

    <section class="admin-panel mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="admin-panel-header flex items-center justify-between gap-4 border-b border-slate-200 px-5 py-4">
            <div>
                <h2 class="admin-panel-title text-base font-bold text-slate-950">{{ __('Recently created workspaces') }}</h2>
                <p class="admin-panel-subtitle mt-0.5 text-xs text-slate-500">{{ __('The six newest tenants in the platform.') }}</p>
            </div>
            <a href="{{ route('admin.tenants.index') }}" class="admin-link inline-flex items-center gap-1.5 text-sm font-semibold text-cyan-700 hover:text-cyan-800">
                {{ __('View all') }}
                <x-icon name="arrow-right" class="h-4 w-4" />
            </a>
        </div>

        @if ($recentTenants->isEmpty())
            <p class="admin-empty px-5 py-10 text-center text-sm text-slate-500">{{ __('No workspaces have been provisioned yet.') }}</p>
        @else
            <div class="admin-table-wrap overflow-x-auto">
                <table class="admin-table w-full min-w-[44rem] text-left text-sm">
                    <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-[0.1em] text-slate-500">
                        <tr>
                            <th scope="col" class="admin-th px-5 py-3">{{ __('Workspace') }}</th>
                            <th scope="col" class="admin-th px-5 py-3">{{ __('Subdomain') }}</th>
                            <th scope="col" class="admin-th px-5 py-3">{{ __('Status') }}</th>
                            <th scope="col" class="admin-th px-5 py-3">{{ __('Created') }}</th>
                            <th scope="col" class="admin-th admin-th--right px-5 py-3 text-right">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($recentTenants as $tenant)
                            <tr>
                                <td class="admin-td px-5 py-3.5">
                                    <p class="admin-row-title font-semibold text-slate-900">{{ $tenant->name }}</p>
                                    <p class="admin-meta admin-mono font-mono text-[11px] text-slate-500">{{ $tenant->getTenantKey() }}</p>
                                </td>
                                <td class="admin-td admin-mono px-5 py-3.5 font-mono text-xs text-slate-600">{{ $tenant->subdomain() ?? $tenant->customDomain() ?? '—' }}</td>
                                <td class="admin-td px-5 py-3.5"><x-admin.status-badge :status="$tenant->status" /></td>
                                <td class="admin-td admin-meta px-5 py-3.5 text-xs text-slate-500">{{ $tenant->created_at?->format('M j, Y') }}</td>
                                <td class="admin-td admin-td--right px-5 py-3.5 text-right">
                                    <a href="{{ route('admin.tenants.show', $tenant) }}" class="admin-link text-sm font-semibold text-cyan-700 hover:text-cyan-800">{{ __('Inspect') }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</x-admin-layout>
