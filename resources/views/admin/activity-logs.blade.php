<x-admin-layout :title="__('Activity Logs')">
    <x-slot name="header">
        <div>
            <p class="admin-eyebrow text-xs font-bold uppercase tracking-[0.18em] text-cyan-700">{{ __('Audit trail') }}</p>
            <h1 class="admin-title mt-2 text-2xl font-extrabold tracking-tight text-slate-950 sm:text-3xl">{{ __('Activity Logs') }}</h1>
            <p class="admin-subtitle mt-1.5 text-sm text-slate-500">{{ __('Central record of tenant provisioning, suspension, and deletion.') }}</p>
        </div>
    </x-slot>

    <section class="admin-panel overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        @if ($activities->isEmpty())
            <p class="admin-empty px-5 py-12 text-center text-sm text-slate-500">{{ __('No super-admin activity has been recorded yet.') }}</p>
        @else
            <div class="admin-table-wrap overflow-x-auto">
                <table class="admin-table admin-table--mid w-full min-w-[48rem] text-left text-sm">
                    <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-[0.1em] text-slate-500">
                        <tr>
                            <th scope="col" class="admin-th px-5 py-3">{{ __('Action') }}</th>
                            <th scope="col" class="admin-th px-5 py-3">{{ __('Event') }}</th>
                            <th scope="col" class="admin-th px-5 py-3">{{ __('Performed by') }}</th>
                            <th scope="col" class="admin-th px-5 py-3">{{ __('When') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($activities as $activity)
                            <tr>
                                <td class="admin-td px-5 py-3.5 font-medium text-slate-900">{{ $activity->description }}</td>
                                <td class="admin-td admin-mono admin-meta px-5 py-3.5 font-mono text-[11px] text-slate-500">{{ $activity->event ?? '—' }}</td>
                                <td class="admin-td admin-meta px-5 py-3.5 text-xs text-slate-600">{{ $activity->causer?->name ?? __('System') }}</td>
                                <td class="admin-td admin-meta px-5 py-3.5 text-xs text-slate-500">{{ $activity->created_at?->format('M j, Y g:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="admin-pagination border-t border-slate-200 px-5 py-4">
                {{ $activities->links() }}
            </div>
        @endif
    </section>
</x-admin-layout>
