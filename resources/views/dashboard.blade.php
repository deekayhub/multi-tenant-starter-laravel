<x-app-layout :title="__('Overview')">
    @php
        $user = auth()->user();
        $tenant = tenant();
        $workspaceName = $tenant?->name ?? config('app.name', 'Orbit');
        $workspaceHost = $tenant?->preferredHost() ?? parse_url((string) config('app.url'), PHP_URL_HOST);
        $profileRoute = $tenant ? route('profile.edit') : route('central.profile.edit');
        $canViewActivity = ! $tenant || $user?->can('activity-logs.view');
        $canViewTeam = (bool) $tenant && (bool) $user?->can('users.view');
        $activityRoute = $tenant ? route('tenant.activity.index') : route('activity.index');

        $shortcuts = [
            ['label' => __('Profile'), 'icon' => 'user', 'href' => $profileRoute],
        ];

        if ($tenant && $user?->can('workspace.view')) {
            $shortcuts[] = ['label' => __('Workspace settings'), 'icon' => 'storefront', 'href' => route('settings.workspace.edit')];
        }

        if ($canViewTeam) {
            $shortcuts[] = ['label' => __('Team members'), 'icon' => 'users', 'href' => route('settings.members.index')];
        }

        if ($tenant && $user?->can('roles.view')) {
            $shortcuts[] = ['label' => __('Roles & permissions'), 'icon' => 'cog', 'href' => route('settings.roles.index')];
        }

        if ($canViewActivity) {
            $shortcuts[] = ['label' => __('Activity log'), 'icon' => 'chart', 'href' => $activityRoute];
        }

        $kpis = [
            [
                'label' => __('Team members'),
                'value' => $memberStats->total,
                'hint' => __(':count verified accounts', ['count' => $memberStats->verified]),
                'icon' => 'users',
            ],
            [
                'label' => __('Active roles'),
                'value' => $roleCount,
                'hint' => __('Access groups in use'),
                'icon' => 'cog',
            ],
            [
                'label' => __('Sign-ins · 30 days'),
                'value' => $signIns30Days,
                'hint' => __('Authentication events'),
                'icon' => 'check',
            ],
            [
                'label' => __('Domains'),
                'value' => $domainCount,
                'hint' => __('Workspace hostnames'),
                'icon' => 'grid',
            ],
        ];
    @endphp

    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4 sm:items-end">
            <div>
                <p class="eyebrow">{{ __('Workspace overview') }}</p>
                <h1 class="mt-2 text-2xl font-semibold tracking-tight text-foreground sm:text-3xl">
                    {{ __('Hello, :name.', ['name' => \Illuminate\Support\Str::of($user->name)->explode(' ')->first()]) }}
                </h1>
                <p class="mt-1.5 max-w-2xl text-sm leading-6 text-muted-foreground">
                    {{ __('Track your workspace activity, team growth, and access at a glance.') }}
                </p>
            </div>
            <a href="{{ $profileRoute }}" class="btn-secondary">
                <x-icon name="user" class="h-4 w-4" />
                {{ __('Open profile') }}
            </a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($kpis as $kpi)
                <section class="surface-card" aria-labelledby="dashboard-kpi-{{ $loop->index }}">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p id="dashboard-kpi-{{ $loop->index }}" class="eyebrow">{{ $kpi['label'] }}</p>
                            <p class="mt-3 text-3xl font-semibold tracking-tight text-foreground">{{ $kpi['value'] }}</p>
                            <p class="mt-1 truncate text-xs text-muted-foreground">{{ $kpi['hint'] }}</p>
                        </div>
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-muted text-foreground">
                            <x-icon :name="$kpi['icon']" class="h-5 w-5" />
                        </span>
                    </div>
                </section>
            @endforeach
        </div>

        <div class="grid gap-4 xl:grid-cols-3">
            <section class="surface-card xl:col-span-2" aria-labelledby="dashboard-trend-heading">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 id="dashboard-trend-heading" class="text-base font-semibold text-foreground">{{ __('Sign-in activity') }}</h2>
                        <p class="mt-1 text-xs text-muted-foreground">{{ __('Authentication events over the last seven days.') }}</p>
                    </div>
                    @if ($canViewActivity)
                        <a href="{{ $activityRoute }}" class="inline-flex items-center gap-1.5 rounded-md text-xs font-semibold text-foreground transition-colors hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                            {{ __('View log') }}
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    @endif
                </div>

                <div class="mt-6 flex h-48 items-end gap-2 sm:gap-3" role="img" aria-label="{{ __('Sign-ins per day for the last seven days') }}">
                    @foreach ($loginTrend as $date => $count)
                        @php
                            $barHeight = $count > 0 ? max(12, (int) round(($count / $chartMax) * 100)) : 6;
                        @endphp
                        <div class="flex h-full flex-1 flex-col items-center justify-end gap-2">
                            <div
                                class="w-full rounded-t-md transition-colors duration-150 {{ $count > 0 ? 'bg-primary' : 'bg-muted' }}"
                                style="height: {{ $barHeight }}%"
                                title="{{ $count }} {{ __('sign-ins') }} · {{ $date }}"
                            ></div>
                            <span class="text-[10px] font-semibold uppercase tracking-wide text-muted-foreground">
                                {{ \Illuminate\Support\Carbon::parse($date)->isoFormat('ddd') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="surface-card" aria-labelledby="dashboard-shortcuts-heading">
                <h2 id="dashboard-shortcuts-heading" class="text-base font-semibold text-foreground">{{ __('Shortcuts') }}</h2>
                <p class="mt-1 text-xs text-muted-foreground">{{ __('Jump back into your most-used settings.') }}</p>
                <div class="mt-4 flex flex-col gap-2">
                    @foreach ($shortcuts as $shortcut)
                        <a href="{{ $shortcut['href'] }}" class="group flex items-center gap-3 rounded-lg border border-line bg-card px-3 py-2.5 shadow-sm transition-colors hover:border-zinc-300 hover:bg-muted/50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-muted text-foreground">
                                <x-icon :name="$shortcut['icon']" class="h-4 w-4" />
                            </span>
                            <span class="min-w-0 flex-1 truncate text-sm font-medium text-foreground">{{ $shortcut['label'] }}</span>
                            <x-icon name="arrow-right" class="h-3.5 w-3.5 shrink-0 text-muted-foreground transition-transform group-hover:translate-x-0.5" />
                        </a>
                    @endforeach
                </div>
            </section>
        </div>

        <section class="surface-card overflow-hidden" aria-labelledby="dashboard-team-heading">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 id="dashboard-team-heading" class="text-base font-semibold text-foreground">{{ __('Team members') }}</h2>
                    <p class="mt-1 text-xs text-muted-foreground">{{ __('The newest accounts in this workspace.') }}</p>
                </div>
                @if ($canViewTeam)
                    <a href="{{ route('settings.members.index') }}" class="inline-flex items-center gap-1.5 rounded-md text-xs font-semibold text-foreground transition-colors hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                        {{ __('Manage team') }}
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                @endif
            </div>

            <div class="mt-4 overflow-x-auto border-t border-line">
                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line bg-muted/50 text-[10px] font-semibold uppercase tracking-[0.12em] text-muted-foreground">
                            <th scope="col" class="px-5 py-2.5 font-semibold">{{ __('Member') }}</th>
                            <th scope="col" class="px-5 py-2.5 font-semibold">{{ __('Role') }}</th>
                            <th scope="col" class="hidden px-5 py-2.5 font-semibold md:table-cell">{{ __('Status') }}</th>
                            <th scope="col" class="px-5 py-2.5 text-right font-semibold">{{ __('Joined') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @forelse ($teamMembers as $member)
                            @php
                                $initials = \Illuminate\Support\Str::of($member->name)->explode(' ')->filter()->take(2)->map(fn (string $part): string => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($part, 0, 1)))->implode('');
                                $roleNames = $member->roles->pluck('name')->join(', ');
                                $isVerified = $member->email_verified_at !== null;
                            @endphp
                            <tr class="transition-colors hover:bg-muted/50">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-muted text-[11px] font-bold text-foreground" aria-hidden="true">{{ $initials }}</span>
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold text-foreground">{{ $member->name }}</p>
                                            <p class="truncate text-xs text-muted-foreground">{{ $member->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    @if ($roleNames !== '')
                                        <span class="inline-flex rounded-md border border-line bg-muted/50 px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.08em] text-muted-foreground">{{ $roleNames }}</span>
                                    @else
                                        <span class="text-xs text-muted-foreground">{{ __('No role') }}</span>
                                    @endif
                                </td>
                                <td class="hidden px-5 py-3 md:table-cell">
                                    <span @class([
                                        'inline-flex items-center gap-1.5 rounded-md border px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.08em]',
                                        'border-success/25 bg-success/10 text-success' => $isVerified,
                                        'border-warning/25 bg-warning/10 text-caution' => ! $isVerified,
                                    ])>
                                        <x-icon :name="$isVerified ? 'check' : 'clock'" class="h-3.5 w-3.5" />
                                        {{ $isVerified ? __('Verified') : __('Pending') }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-right text-xs font-medium text-muted-foreground">
                                    <time datetime="{{ $member->created_at?->toIso8601String() }}" title="{{ $member->created_at?->format('M j, Y, g:i A') }}">{{ $member->created_at?->diffForHumans() }}</time>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-sm text-muted-foreground">
                                    {{ __('No team members yet. Invite someone to get started.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="surface-card overflow-hidden" aria-labelledby="dashboard-activity-heading">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 id="dashboard-activity-heading" class="text-base font-semibold text-foreground">{{ __('Recent activity') }}</h2>
                    <p class="mt-1 text-xs text-muted-foreground">{{ __('Latest sign-in and sign-out events.') }}</p>
                </div>
                @if ($canViewActivity)
                    <a href="{{ $activityRoute }}" class="inline-flex items-center gap-1.5 rounded-md text-xs font-semibold text-foreground transition-colors hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                        {{ __('View all') }}
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                @endif
            </div>

            <div class="mt-4 overflow-x-auto border-t border-line">
                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line bg-muted/50 text-[10px] font-semibold uppercase tracking-[0.12em] text-muted-foreground">
                            <th scope="col" class="px-5 py-2.5 font-semibold">{{ __('Event') }}</th>
                            <th scope="col" class="px-5 py-2.5 font-semibold">{{ __('Subject') }}</th>
                            <th scope="col" class="hidden px-5 py-2.5 font-semibold sm:table-cell">{{ __('Type') }}</th>
                            <th scope="col" class="px-5 py-2.5 text-right font-semibold">{{ __('When') }}</th>
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
                                        'inline-flex items-center gap-1.5 rounded-md border px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.08em]',
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
                                    <span class="text-[10px] font-semibold uppercase tracking-[0.08em] text-muted-foreground">{{ $isLogin ? __('Sign-in') : __('Sign-out') }}</span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-3 text-right text-xs font-medium text-muted-foreground">
                                    <time datetime="{{ $activity->created_at?->toIso8601String() }}" title="{{ $activity->created_at?->format('M j, Y, g:i A') }}">{{ $activity->created_at?->diffForHumans() }}</time>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-sm text-muted-foreground">
                                    {{ __('No activity yet. Sign-ins and sign-outs will appear here.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
