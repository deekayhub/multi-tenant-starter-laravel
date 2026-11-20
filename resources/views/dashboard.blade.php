<x-app-layout :title="__('Overview')">
    @php
        $user = auth()->user();
        $tenant = tenant();
        $workspaceName = $tenant?->name ?? config('app.name', 'Orbit');
        $workspaceHost = $tenant?->preferredHost() ?? parse_url((string) config('app.url'), PHP_URL_HOST);
        $canViewActivity = ! $tenant || $user?->can('activity-logs.view');
        $activityRoute = $tenant ? route('tenant.activity.index') : route('activity.index');

        $shortcuts = [
            ['label' => __('Profile'), 'icon' => 'user', 'href' => route('profile.edit')],
        ];

        if ($tenant && $user?->can('workspace.view')) {
            $shortcuts[] = ['label' => __('Workspace settings'), 'icon' => 'storefront', 'href' => route('settings.workspace.edit')];
        }

        if ($tenant && $user?->can('users.view')) {
            $shortcuts[] = ['label' => __('Team members'), 'icon' => 'users', 'href' => route('settings.members.index')];
        }

        if ($tenant && $user?->can('roles.view')) {
            $shortcuts[] = ['label' => __('Roles & permissions'), 'icon' => 'cog', 'href' => route('settings.roles.index')];
        }

        if ($canViewActivity) {
            $shortcuts[] = ['label' => __('Activity log'), 'icon' => 'chart', 'href' => $activityRoute];
        }
    @endphp

    <div class="space-y-6">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="eyebrow">{{ __('Workspace overview') }}</p>
                <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-foreground sm:text-3xl">
                    {{ __('Hello, :name.', ['name' => \Illuminate\Support\Str::of($user->name)->explode(' ')->first()]) }}
                </h1>
                <p class="mt-1.5 max-w-2xl text-sm text-muted-foreground">
                    {{ __('A placeholder dashboard for this multi-tenancy starter kit. The authentication, tenancy and permissions behind it are real.') }}
                </p>
            </div>
            <a href="{{ route('profile.edit') }}" class="btn-secondary">
                <x-icon name="user" class="h-4 w-4" />
                {{ __('Open profile') }}
            </a>
        </div>

        <div class="grid gap-3 md:grid-cols-3">
            <section class="surface-card" aria-labelledby="dashboard-workspace-heading">
                <h2 id="dashboard-workspace-heading" class="text-[10px] font-bold uppercase tracking-[0.14em] text-muted-foreground">{{ __('Workspace') }}</h2>
                <p class="mt-2 truncate text-lg font-extrabold text-foreground">{{ $workspaceName }}</p>
                <p class="mt-1 truncate font-mono text-xs text-muted-foreground">{{ $workspaceHost ?: '—' }}</p>
            </section>

            <section class="surface-card" aria-labelledby="dashboard-account-heading">
                <h2 id="dashboard-account-heading" class="text-[10px] font-bold uppercase tracking-[0.14em] text-muted-foreground">{{ __('Account') }}</h2>
                <p class="mt-2 truncate text-lg font-extrabold text-foreground">{{ $user->name }}</p>
                <p class="mt-1 truncate text-xs text-muted-foreground">{{ $user->email }}</p>
            </section>

            <section class="surface-card" aria-labelledby="dashboard-starter-heading">
                <h2 id="dashboard-starter-heading" class="text-[10px] font-bold uppercase tracking-[0.14em] text-muted-foreground">{{ __('Starter kit') }}</h2>
                <p class="mt-2 text-sm font-semibold text-foreground">{{ __('Ready to replace') }}</p>
                <p class="mt-1 text-xs leading-5 text-muted-foreground">{{ __('These cards are placeholders. Keep the shell and swap in your own product screens.') }}</p>
            </section>
        </div>

        <section class="surface-card" aria-labelledby="dashboard-shortcuts-heading">
            <h2 id="dashboard-shortcuts-heading" class="text-sm font-extrabold text-foreground">{{ __('Shortcuts') }}</h2>
            <div class="mt-3 grid gap-2 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($shortcuts as $shortcut)
                    <a href="{{ $shortcut['href'] }}" class="group flex items-center gap-3 rounded-lg border border-line bg-white/70 px-3 py-2.5 transition-colors hover:border-primary/40 hover:bg-primary/5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-primary/10 text-primary">
                            <x-icon :name="$shortcut['icon']" class="h-4 w-4" />
                        </span>
                        <span class="min-w-0 flex-1 truncate text-xs font-bold text-foreground">{{ $shortcut['label'] }}</span>
                        <x-icon name="arrow-right" class="h-3.5 w-3.5 shrink-0 text-muted-foreground transition-transform group-hover:translate-x-0.5" />
                    </a>
                @endforeach
            </div>
        </section>

        <section class="surface-card overflow-hidden" aria-labelledby="dashboard-activity-heading">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 id="dashboard-activity-heading" class="text-sm font-extrabold text-foreground">{{ __('Recent activity') }}</h2>
                    <p class="mt-1 text-xs text-muted-foreground">{{ __('Latest sign-in and sign-out events.') }}</p>
                </div>
                @if ($canViewActivity)
                    <a href="{{ $activityRoute }}" class="inline-flex items-center gap-1.5 rounded text-[11px] font-bold uppercase tracking-[0.1em] text-primary transition-colors hover:text-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                        {{ __('View all') }}
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                @endif
            </div>

            <div class="mt-3 overflow-x-auto border-t border-line">
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
                                <td colspan="4" class="px-5 py-8 text-center text-xs font-medium text-muted-foreground">
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
