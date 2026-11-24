<x-admin-layout :title="__('Tenant Details')">
    <x-slot name="header">
        <div class="admin-page-header-row flex flex-wrap items-end justify-between gap-x-6 gap-y-4">
            <div class="min-w-0">
                <p class="admin-eyebrow text-[11px] font-semibold uppercase tracking-[0.16em] text-zinc-500">{{ __('Workspace inspection') }}</p>
                <h1 class="admin-title mt-2 break-words text-2xl font-semibold tracking-tight text-zinc-950 sm:text-[28px]">{{ $tenant->name }}</h1>
                <p class="admin-meta admin-mono mt-2 font-mono text-xs text-zinc-500">{{ $tenant->getTenantKey() }}</p>
            </div>
            <div class="admin-actions flex flex-wrap items-center justify-end gap-2">
                @if ($tenant->preferredHost())
                    <a href="{{ route('admin.tenants.impersonate', $tenant) }}" class="admin-button admin-button--secondary btn-secondary">
                        <x-icon name="arrow-right" class="h-4 w-4" />
                        {{ __('Login as Workspace') }}
                    </a>
                @endif
                <a href="{{ route('admin.tenants.index') }}" class="admin-button admin-button--quiet btn-quiet">{{ __('Back to tenants') }}</a>
            </div>
        </div>
    </x-slot>

    <div class="admin-detail-grid grid gap-5 xl:grid-cols-[minmax(0,2fr)_minmax(18rem,1fr)]">
        <div class="admin-detail-stack space-y-5">
            <section class="admin-panel overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
                <div class="admin-panel-header border-b border-zinc-200 px-5 py-4">
                    <h2 class="admin-panel-title text-sm font-semibold tracking-tight text-zinc-950">{{ __('Workspace information') }}</h2>
                </div>
                <dl class="admin-dl grid gap-px bg-zinc-200 sm:grid-cols-2">
                    <div class="admin-dl-item bg-white px-5 py-3.5">
                        <dt class="admin-dl-label text-[11px] font-semibold uppercase tracking-[0.1em] text-zinc-500">{{ __('Owner email') }}</dt>
                        <dd class="admin-dl-value mt-1 text-sm font-semibold text-zinc-900">{{ $tenant->email ?? '—' }}</dd>
                    </div>
                    <div class="admin-dl-item bg-white px-5 py-3.5">
                        <dt class="admin-dl-label text-[11px] font-semibold uppercase tracking-[0.1em] text-zinc-500">{{ __('Status') }}</dt>
                        <dd class="mt-1"><x-admin.status-badge :status="$tenant->status" /></dd>
                    </div>
                    <div class="admin-dl-item bg-white px-5 py-3.5">
                        <dt class="admin-dl-label text-[11px] font-semibold uppercase tracking-[0.1em] text-zinc-500">{{ __('Members') }}</dt>
                        <dd class="admin-dl-value mt-1 text-sm font-semibold text-zinc-900">{{ $memberCount }}</dd>
                    </div>
                    <div class="admin-dl-item bg-white px-5 py-3.5">
                        <dt class="admin-dl-label text-[11px] font-semibold uppercase tracking-[0.1em] text-zinc-500">{{ __('Created') }}</dt>
                        <dd class="admin-dl-value mt-1 text-sm font-semibold text-zinc-900">{{ $tenant->created_at?->format('M j, Y g:i A') }}</dd>
                    </div>
                </dl>
            </section>

            <section class="admin-panel overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
                <div class="admin-panel-header border-b border-zinc-200 px-5 py-4">
                    <h2 class="admin-panel-title text-sm font-semibold tracking-tight text-zinc-950">{{ __('Recent tenant activity') }}</h2>
                </div>
                @if ($recentActivity->isEmpty())
                    <p class="admin-empty px-5 py-10 text-center text-sm text-zinc-500">{{ __('No activity recorded for this workspace yet.') }}</p>
                @else
                    <div class="admin-table-wrap overflow-x-auto">
                        <table class="admin-table admin-table--narrow w-full min-w-[36rem] text-left text-sm">
                            <thead class="bg-zinc-50 text-[11px] font-semibold uppercase tracking-[0.1em] text-zinc-500">
                                <tr>
                                    <th scope="col" class="admin-th px-5 py-3">{{ __('Description') }}</th>
                                    <th scope="col" class="admin-th px-5 py-3">{{ __('Causer') }}</th>
                                    <th scope="col" class="admin-th px-5 py-3">{{ __('When') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @foreach ($recentActivity as $activity)
                                    <tr class="transition-colors hover:bg-zinc-50/70">
                                        <td class="admin-td px-5 py-3 font-medium text-zinc-800">{{ $activity->description }}</td>
                                        <td class="admin-td admin-meta px-5 py-3 text-xs text-zinc-500">{{ $activity->causer?->name ?? __('System') }}</td>
                                        <td class="admin-td admin-meta px-5 py-3 text-xs text-zinc-500">{{ $activity->created_at?->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>

        <div class="admin-detail-stack space-y-5">
            <section class="admin-panel admin-panel-body rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <h2 class="admin-panel-title text-sm font-semibold tracking-tight text-zinc-950">{{ __('Domains') }}</h2>
                <ul class="admin-domain-list mt-4 space-y-2">
                    @forelse ($tenant->domains as $domain)
                        <li class="admin-domain admin-mono rounded-lg border border-zinc-200 bg-zinc-50 px-3 py-2 font-mono text-xs text-zinc-700">
                            {{ $domain->domain }}
                        </li>
                    @empty
                        <li class="admin-text-muted text-sm text-zinc-500">{{ __('No domains assigned.') }}</li>
                    @endforelse
                </ul>
                @if ($tenant->preferredHost())
                    <a href="{{ tenant_route($tenant->preferredHost(), 'tenant.dashboard') }}" class="admin-link mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-zinc-600 hover:text-zinc-900">
                        {{ __('Open workspace') }}
                        <x-icon name="arrow-right" class="h-4 w-4" />
                    </a>
                @endif
            </section>

            <section class="admin-panel admin-panel-body rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <h2 class="admin-panel-title text-sm font-semibold tracking-tight text-zinc-950">{{ __('Workspace controls') }}</h2>
                <p class="admin-panel-subtitle mt-1.5 text-xs leading-5 text-zinc-500">{{ __('Status changes take effect immediately on the tenant domain.') }}</p>

                <form method="POST" action="{{ route('admin.tenants.toggle-status', $tenant) }}" class="mt-4" onsubmit="return confirm('{{ $tenant->status === \App\Models\Tenant::STATUS_ACTIVE ? __('Suspend this workspace?') : __('Reactivate this workspace?') }}')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="admin-button admin-button--secondary admin-button--full btn-secondary">
                        {{ $tenant->status === \App\Models\Tenant::STATUS_ACTIVE ? __('Suspend Workspace') : __('Reactivate Workspace') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.tenants.destroy', $tenant) }}" class="mt-2" onsubmit="return confirm('{{ __('Delete this workspace and all of its tenant data? This cannot be undone.') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-button admin-button--danger admin-button--full">
                        <x-icon name="x" class="h-4 w-4" />
                        {{ __('Delete Workspace') }}
                    </button>
                </form>
            </section>
        </div>
    </div>
</x-admin-layout>
