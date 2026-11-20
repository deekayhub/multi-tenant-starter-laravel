<x-app-layout :title="__('Group activity')">
    @php
        $activityRoute = tenant() ? route('tenant.activity.index') : route('activity.index');

        $filters = [
            ['key' => 'all', 'label' => __('All events'), 'count' => $counts['all']],
            ['key' => 'login', 'label' => __('Sign-ins'), 'count' => $counts['login']],
            ['key' => 'logout', 'label' => __('Sign-outs'), 'count' => $counts['logout']],
        ];
    @endphp

    <div class="space-y-7">
        <!-- Page heading -->
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.12em] text-muted-foreground">
                    <x-icon name="clock" class="h-4 w-4" />
                    {{ __('Security log') }}
                </p>
                <h1 class="mt-3 text-4xl font-extrabold leading-tight tracking-tight text-foreground sm:text-5xl">{{ __('Group activity') }}</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-muted-foreground">
                    {{ __('Sign-ins and sign-outs across your workspace, newest first.') }}
                </p>
            </div>

            <a href="{{ $activityRoute }}" class="btn-secondary self-start">
                <x-icon name="sync" class="h-4 w-4" />
                {{ __('Refresh') }}
            </a>
        </div>

        <!-- Summary -->
        <div class="grid gap-4 sm:grid-cols-3">
            @foreach ($filters as $filter)
                <div class="surface-card transition-transform duration-150 hover:-translate-y-0.5">
                    <div class="flex items-start justify-between gap-3">
                        <p class="text-xs font-bold uppercase tracking-[0.1em] text-muted-foreground">{{ $filter['label'] }}</p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10 text-primary">
                            <x-icon :name="$filter['key'] === 'logout' ? 'logout' : ($filter['key'] === 'login' ? 'check' : 'chart')" class="h-5 w-5" />
                        </span>
                    </div>
                    <p class="mt-4 text-3xl font-extrabold tracking-tight text-foreground">{{ $filter['count'] }}</p>
                </div>
            @endforeach
        </div>

        <!-- Activity table -->
        <section class="glass-card overflow-hidden" aria-labelledby="activity-log-heading">
            <div class="flex flex-col gap-4 p-5 pb-4 xl:flex-row xl:items-center xl:justify-between">
                <div>
                    <h2 id="activity-log-heading" class="text-xl font-extrabold tracking-tight text-foreground">{{ __('Activity log') }}</h2>
                    <p class="mt-1 text-xs text-muted-foreground">{{ __('Authentication events for everyone in your group.') }}</p>
                </div>

                <nav class="inline-flex flex-wrap gap-1 rounded-xl border border-line bg-white/70 p-1" aria-label="{{ __('Filter activity') }}">
                    @foreach ($filters as $filter)
                        <a
                            href="{{ route(tenant() ? 'tenant.activity.index' : 'activity.index', $filter['key'] === 'all' ? [] : ['event' => $filter['key']]) }}"
                            @if ($eventFilter === $filter['key']) aria-current="page" @endif
                            @class([
                                'inline-flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-bold transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary',
                                'bg-primary text-white shadow-sm' => $eventFilter === $filter['key'],
                                'text-muted-foreground hover:bg-muted hover:text-foreground' => $eventFilter !== $filter['key'],
                            ])
                        >
                            {{ $filter['label'] }}
                            <span class="rounded-md bg-white/20 px-1.5 py-0.5 text-[10px] font-bold {{ $eventFilter === $filter['key'] ? 'text-white' : 'text-muted-foreground' }}">{{ $filter['count'] }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>

            @if ($activities->isEmpty())
                <div class="flex flex-col items-center gap-3 border-t border-line px-5 py-16 text-center">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                        <x-icon name="clock" class="h-7 w-7" />
                    </span>
                    <p class="text-sm font-bold text-foreground">{{ __('No activity to show') }}</p>
                    <p class="max-w-sm text-xs leading-5 text-muted-foreground">
                        {{ $eventFilter === 'all' ? __('Sign-ins and sign-outs will appear here as your group uses the app.') : __('Try another filter to see more events.') }}
                    </p>
                </div>
            @else
                <div class="overflow-x-auto border-t border-line">
                    <table class="min-w-full text-left text-sm">
                        <caption class="sr-only">{{ __('Group authentication activity') }}</caption>
                        <thead>
                            <tr class="text-[10px] font-bold uppercase tracking-[0.12em] text-muted-foreground">
                                <th scope="col" class="px-5 py-2.5 font-bold">{{ __('User') }}</th>
                                <th scope="col" class="px-5 py-2.5 font-bold">{{ __('Event') }}</th>
                                <th scope="col" class="hidden px-5 py-2.5 font-bold md:table-cell">{{ __('IP address') }}</th>
                                <th scope="col" class="hidden px-5 py-2.5 font-bold lg:table-cell">{{ __('Device') }}</th>
                                <th scope="col" class="px-5 py-2.5 text-right font-bold">{{ __('When') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @foreach ($activities as $activity)
                                @php
                                    $user = $activity->causer;
                                    $userName = $user?->name ?? __('Deleted user');
                                    $initials = \Illuminate\Support\Str::of($userName)->explode(' ')->filter()->take(2)->map(fn (string $part): string => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($part, 0, 1)))->implode('');
                                    $isLogin = $activity->event === 'login';
                                    $ipAddress = $activity->getProperty('ip_address');
                                    $userAgent = $activity->getProperty('user_agent');
                                @endphp
                                <tr class="transition-colors hover:bg-muted/40">
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-secondary to-primary text-[11px] font-bold text-white" aria-hidden="true">{{ $initials }}</span>
                                            <div class="min-w-0">
                                                <p class="truncate text-xs font-bold text-foreground">{{ $userName }}</p>
                                                <p class="truncate text-[11px] font-medium text-muted-foreground">{{ $user?->email ?? '—' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3">
                                        <span @class([
                                            'inline-flex items-center gap-1.5 rounded-md border px-2 py-1 text-[10px] font-bold uppercase tracking-[0.08em]',
                                            'border-success/25 bg-success/10 text-success' => $isLogin,
                                            'border-warning/25 bg-warning/10 text-caution' => ! $isLogin,
                                        ])>
                                            <x-icon :name="$isLogin ? 'check' : 'logout'" class="h-3.5 w-3.5" />
                                            {{ $isLogin ? __('Signed in') : __('Signed out') }}
                                        </span>
                                    </td>
                                    <td class="hidden px-5 py-3 font-mono text-xs text-muted-foreground md:table-cell">{{ $ipAddress ?: '—' }}</td>
                                    <td class="hidden px-5 py-3 lg:table-cell">
                                        @if ($userAgent)
                                            <span class="block max-w-[18rem] truncate text-xs text-muted-foreground" title="{{ $userAgent }}">{{ \Illuminate\Support\Str::limit($userAgent, 44) }}</span>
                                        @else
                                            <span class="text-xs text-muted-foreground">—</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3 text-right">
                                        <time datetime="{{ $activity->created_at?->toIso8601String() }}" title="{{ $activity->created_at?->format('M j, Y, g:i A') }}" class="text-xs font-semibold text-foreground">{{ $activity->created_at?->diffForHumans() }}</time>
                                        <span class="mt-0.5 block text-[11px] font-medium text-muted-foreground">{{ $activity->created_at?->format('M j, Y, g:i A') }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col gap-3 border-t border-line px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs font-medium text-muted-foreground">
                        {{ __('Showing :first–:last of :total', ['first' => $activities->firstItem(), 'last' => $activities->lastItem(), 'total' => $activities->total()]) }}
                    </p>
                    {{ $activities->links() }}
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
