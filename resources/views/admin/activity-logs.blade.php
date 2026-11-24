<x-admin-layout :title="__('Activity Logs')">
    <x-slot name="header">
        <div class="min-w-0">
            <p class="admin-eyebrow text-[11px] font-semibold uppercase tracking-[0.16em] text-zinc-500">{{ __('Audit trail') }}</p>
            <h1 class="admin-title mt-2 text-2xl font-semibold tracking-tight text-zinc-950 sm:text-[28px]">{{ __('Activity Logs') }}</h1>
            <p class="admin-subtitle mt-2 text-sm leading-6 text-zinc-500">{{ __('Central record of tenant provisioning, suspension, and deletion.') }}</p>
        </div>
    </x-slot>

    <section class="admin-panel overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
        @if ($activities->isEmpty())
            <p class="admin-empty px-5 py-12 text-center text-sm text-zinc-500">{{ __('No super-admin activity has been recorded yet.') }}</p>
        @else
            <div class="admin-table-wrap overflow-x-auto">
                <table class="admin-table admin-table--mid w-full min-w-[48rem] text-left text-sm">
                    <thead class="bg-zinc-50 text-[11px] font-semibold uppercase tracking-[0.1em] text-zinc-500">
                        <tr>
                            <th scope="col" class="admin-th px-5 py-3">{{ __('Action') }}</th>
                            <th scope="col" class="admin-th px-5 py-3">{{ __('Event') }}</th>
                            <th scope="col" class="admin-th px-5 py-3">{{ __('Performed by') }}</th>
                            <th scope="col" class="admin-th px-5 py-3">{{ __('When') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @foreach ($activities as $activity)
                            <tr class="transition-colors hover:bg-zinc-50/70">
                                <td class="admin-td px-5 py-3.5 font-medium text-zinc-900">{{ $activity->description }}</td>
                                <td class="admin-td admin-mono admin-meta px-5 py-3.5 font-mono text-[11px] text-zinc-500">{{ $activity->event ?? '—' }}</td>
                                <td class="admin-td admin-meta px-5 py-3.5 text-xs text-zinc-600">{{ $activity->causer?->name ?? __('System') }}</td>
                                <td class="admin-td admin-meta px-5 py-3.5 text-xs text-zinc-500">{{ $activity->created_at?->format('M j, Y g:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="admin-pagination border-t border-zinc-200 px-5 py-4">
                {{ $activities->links() }}
            </div>
        @endif
    </section>
</x-admin-layout>
