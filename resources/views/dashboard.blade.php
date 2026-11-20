<x-app-layout :title="__('Overview')">
    @php
        $hour = (int) now()->format('G');
        $greeting = $hour < 12 ? __('Good morning') : ($hour < 18 ? __('Good afternoon') : __('Good evening'));
        $firstName = \Illuminate\Support\Str::of(auth()->user()->name)->explode(' ')->first();

        $attention = [
            ['title' => __('Pending invites'), 'detail' => __('3 people have not joined yet'), 'tone' => 'warning'],
            ['title' => __('Trial ending soon'), 'detail' => __('Globex Labs · 2 days left'), 'tone' => 'critical'],
            ['title' => __('Unverified domains'), 'detail' => __('1 domain awaiting verification'), 'tone' => 'warning'],
        ];

        $connections = [
            ['name' => 'Webhooks', 'detail' => __('3 endpoints configured'), 'icon' => 'sync'],
            ['name' => 'API keys', 'detail' => __('2 active keys'), 'icon' => 'plug'],
        ];

        $canViewActivity = ! tenant() || auth()->user()?->can('activity-logs.view');
        $activityRoute = tenant() ? route('tenant.activity.index') : route('activity.index');
    @endphp

    <div class="space-y-7">
        <!-- Page heading -->
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.12em] text-muted-foreground">
                    <x-icon name="clock" class="h-4 w-4" />
                    {{ now()->format('l, j F Y') }}
                </p>
                <h1 class="mt-3 text-4xl font-extrabold leading-tight tracking-tight text-foreground sm:text-5xl">{{ $greeting }}, {{ $firstName }}.</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-muted-foreground">
                    {{ __('Here is a snapshot of the starter workspace. Every number and record below is placeholder content.') }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="#" class="btn-secondary">
                    <x-icon name="download" class="h-4 w-4" />
                    {{ __('Export report') }}
                </a>
                <a href="#" class="btn-accent">
                    <x-icon name="plus" class="h-4 w-4" />
                    {{ __('New tenant') }}
                </a>
            </div>
        </div>

        <!-- Key metrics -->
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['label' => __('Active tenants'), 'value' => '12', 'note' => '+3 '.__('this month'), 'icon' => 'storefront', 'noteClass' => 'text-muted-foreground'],
                ['label' => __('Monthly revenue'), 'value' => '$8,940', 'note' => '+8.4% '.__('vs last month'), 'icon' => 'wallet', 'noteClass' => 'text-success'],
                ['label' => __('Active members'), 'value' => '248', 'note' => '+38 '.__('this week'), 'icon' => 'users', 'noteClass' => 'text-muted-foreground'],
                ['label' => __('Uptime'), 'value' => '99.98%', 'note' => __('All systems normal'), 'icon' => 'check', 'noteClass' => 'text-success'],
            ] as $metric)
                <div class="surface-card transition-transform duration-150 hover:-translate-y-0.5">
                    <div class="flex items-start justify-between gap-3">
                        <p class="text-xs font-bold uppercase tracking-[0.1em] text-muted-foreground">{{ $metric['label'] }}</p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10 text-primary">
                            <x-icon :name="$metric['icon']" class="h-5 w-5" />
                        </span>
                    </div>
                    <p class="mt-4 text-4xl font-extrabold tracking-tight text-foreground">{{ $metric['value'] }}</p>
                    <p class="mt-2 text-xs font-semibold {{ $metric['noteClass'] }}">{{ $metric['note'] }}</p>
                </div>
            @endforeach
        </div>

        <!-- Chart & attention -->
        <div class="grid gap-4 xl:grid-cols-3">
            <section class="surface-card xl:col-span-2" aria-labelledby="activity-chart-heading">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h2 id="activity-chart-heading" class="text-xl font-extrabold tracking-tight text-foreground">{{ __('Workspace activity') }}</h2>
                        <p class="mt-1 text-xs text-muted-foreground">{{ __('New tenants and member invites, last 7 days') }}</p>
                    </div>
                    <div class="flex items-center gap-4 text-[11px] font-bold uppercase tracking-[0.1em] text-muted-foreground">
                        <span class="inline-flex items-center gap-1.5">
                            <span class="h-2.5 w-2.5 rounded-full bg-primary" aria-hidden="true"></span>
                            {{ __('Tenants') }}
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="h-0.5 w-4 bg-secondary" aria-hidden="true"></span>
                            {{ __('Invites') }}
                        </span>
                    </div>
                </div>

                <div class="mt-6">
                    <svg viewBox="0 0 720 240" class="h-56 w-full sm:h-64" role="img" aria-labelledby="activity-chart-title activity-chart-desc">
                        <title id="activity-chart-title">{{ __('Workspace activity over the last seven days') }}</title>
                        <desc id="activity-chart-desc">{{ __('Tenant creation rises from 2 on Monday to 9 on Sunday. Invites peak at 14 on Friday.') }}</desc>

                        <defs>
                            <linearGradient id="activity-fill" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#2563EB" stop-opacity="0.25" />
                                <stop offset="100%" stop-color="#2563EB" stop-opacity="0" />
                            </linearGradient>
                        </defs>

                        <g stroke="#E2E8F0" stroke-width="1">
                            <line x1="40" y1="30" x2="700" y2="30" />
                            <line x1="40" y1="75" x2="700" y2="75" />
                            <line x1="40" y1="120" x2="700" y2="120" />
                            <line x1="40" y1="165" x2="700" y2="165" />
                            <line x1="40" y1="210" x2="700" y2="210" />
                        </g>

                        <path d="M40 186 150 162 260 174 370 132 480 144 590 96 700 108 V210 H40 Z" fill="url(#activity-fill)" />
                        <path d="M40 186 150 162 260 174 370 132 480 144 590 96 700 108" fill="none" stroke="#2563EB" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M40 150 150 138 260 156 370 108 480 66 590 114 700 72" fill="none" stroke="#3B82F6" stroke-width="2" stroke-dasharray="5 5" stroke-linecap="round" stroke-linejoin="round" />

                        <circle cx="700" cy="108" r="8" fill="#2563EB" fill-opacity="0.18" />
                        <circle cx="700" cy="108" r="4" fill="#2563EB" stroke="#fff" stroke-width="2" />

                        <g fill="#64748B" font-size="11" font-family="Plus Jakarta Sans, sans-serif" text-anchor="middle">
                            <text x="40" y="232">MON</text>
                            <text x="150" y="232">TUE</text>
                            <text x="260" y="232">WED</text>
                            <text x="370" y="232">THU</text>
                            <text x="480" y="232">FRI</text>
                            <text x="590" y="232">SAT</text>
                            <text x="700" y="232">SUN</text>
                        </g>
                    </svg>
                </div>

                <div class="mt-3 flex flex-wrap items-center justify-between gap-2 border-t border-line pt-4 text-xs text-muted-foreground">
                    <p><span class="font-bold text-foreground">31 tenants</span> created this week</p>
                    <p class="font-semibold text-success">&bull; {{ __('On track for the monthly target') }}</p>
                </div>
            </section>

            <section class="surface-card overflow-hidden" aria-labelledby="attention-heading">
                <div class="flex items-start justify-between gap-3 p-5 pb-4">
                    <div>
                        <h2 id="attention-heading" class="text-xl font-extrabold tracking-tight text-foreground">{{ __('Needs attention') }}</h2>
                        <p class="mt-1 text-xs text-muted-foreground">{{ __('Placeholder items for the starter') }}</p>
                    </div>
                    <span class="rounded-lg border border-line bg-muted px-2 py-1 text-[11px] font-bold text-muted-foreground">03</span>
                </div>

                <ul class="divide-y divide-line border-t border-line">
                    @foreach ($attention as $item)
                        <li class="px-5 py-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-foreground">{{ $item['title'] }}</p>
                                    <p class="mt-1 text-[11px] font-medium text-muted-foreground">{{ $item['detail'] }}</p>
                                </div>
                                <span @class([
                                    'shrink-0 rounded-md border px-1.5 py-0.5 text-[10px] font-bold uppercase',
                                    'border-warning/25 bg-warning/10 text-caution' => $item['tone'] === 'warning',
                                    'border-destructive/25 bg-destructive/10 text-destructive' => $item['tone'] === 'critical',
                                ])>{{ $item['tone'] }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="p-4">
                    <a href="#" class="btn-secondary w-full">
                        {{ __('Review setup') }}
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </section>
        </div>

        <!-- Activity & shortcuts -->
        <div class="grid gap-4 xl:grid-cols-3">
            <section class="surface-card overflow-hidden xl:col-span-2" aria-labelledby="recent-activity-heading">
                <div class="flex flex-wrap items-center justify-between gap-3 p-5 pb-4">
                    <div>
                        <h2 id="recent-activity-heading" class="text-xl font-extrabold tracking-tight text-foreground">{{ __('Recent activity') }}</h2>
                        <p class="mt-1 text-xs text-muted-foreground">{{ __('Sign-ins and sign-outs from your group') }}</p>
                    </div>
                    @if ($canViewActivity)
                        <a href="{{ $activityRoute }}" class="inline-flex items-center gap-1.5 rounded text-[11px] font-bold uppercase tracking-[0.1em] text-primary transition-colors hover:text-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                            {{ __('View all') }}
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    @endif
                </div>

                <div class="overflow-x-auto border-t border-line">
                    <table class="min-w-full text-left text-sm">
                        <thead>
                            <tr class="text-[10px] font-bold uppercase tracking-[0.12em] text-muted-foreground">
                                <th scope="col" class="px-5 py-2.5 font-bold">{{ __('Event') }}</th>
                                <th scope="col" class="px-5 py-2.5 font-bold">{{ __('Subject') }}</th>
                                <th scope="col" class="hidden px-5 py-2.5 font-bold sm:table-cell">{{ __('Type') }}</th>
                                <th scope="col" class="px-5 py-2.5 text-right font-bold">{{ __('When') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @forelse ($recentActivity as $activity)
                                @php
                                    $activityUser = $activity->causer;
                                    $isLogin = $activity->event === 'login';
                                @endphp
                                <tr class="transition-colors hover:bg-muted/50">
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
                                    <td class="px-5 py-3 text-xs font-medium text-muted-foreground">
                                        {{ $activityUser?->name ?? __('Deleted user') }}
                                    </td>
                                    <td class="hidden px-5 py-3 sm:table-cell">
                                        <span class="text-[10px] font-bold uppercase tracking-[0.08em] text-muted-foreground">{{ $isLogin ? __('Sign-in') : __('Sign-out') }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3 text-right text-xs font-medium text-muted-foreground">
                                        <time datetime="{{ $activity->created_at?->toIso8601String() }}" title="{{ $activity->created_at?->format('M j, Y, g:i A') }}">{{ $activity->created_at?->diffForHumans() }}</time>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center text-xs font-medium text-muted-foreground">
                                        {{ __('No activity yet. Sign-ins and sign-outs will appear here.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <div class="space-y-4">
                <section class="surface-card" aria-labelledby="quick-actions-heading">
                    <div class="p-5 pb-0">
                        <h2 id="quick-actions-heading" class="text-xl font-extrabold tracking-tight text-foreground">{{ __('Quick actions') }}</h2>
                        <p class="mt-1 text-xs text-muted-foreground">{{ __('Common starter tasks') }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-2 p-4">
                        @foreach ([
                            ['label' => __('Add tenant'), 'icon' => 'plus'],
                            ['label' => __('Invite member'), 'icon' => 'users'],
                            ['label' => __('New project'), 'icon' => 'clipboard'],
                            ['label' => __('View reports'), 'icon' => 'chart'],
                        ] as $action)
                            <a href="#" class="group flex flex-col gap-3 rounded-xl border border-line bg-white/60 p-3 transition-all duration-150 hover:-translate-y-0.5 hover:border-primary/40 hover:bg-primary/5 hover:shadow-glass focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                    <x-icon :name="$action['icon']" class="h-4 w-4" />
                                </span>
                                <span class="text-xs font-bold text-foreground">{{ $action['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </section>

                <section class="surface-card" aria-labelledby="connections-heading">
                    <div class="flex items-center justify-between gap-3 p-5 pb-0">
                        <h2 id="connections-heading" class="text-xl font-extrabold tracking-tight text-foreground">{{ __('Connections') }}</h2>
                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-success/25 bg-success/10 px-2 py-1 text-[10px] font-bold uppercase text-success">
                            <span class="h-1.5 w-1.5 rounded-full bg-success" aria-hidden="true"></span>
                            {{ __('Ready') }}
                        </span>
                    </div>

                    <ul class="divide-y divide-line px-5 pb-2 pt-3">
                        @foreach ($connections as $connection)
                            <li class="flex items-center gap-3 py-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                    <x-icon :name="$connection['icon']" class="h-4 w-4" />
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-xs font-bold text-foreground">{{ $connection['name'] }}</p>
                                    <p class="truncate text-[10px] font-medium text-muted-foreground">{{ $connection['detail'] }}</p>
                                </div>
                                <x-icon name="arrow-right" class="h-3.5 w-3.5 shrink-0 text-muted-foreground" />
                            </li>
                        @endforeach
                    </ul>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
