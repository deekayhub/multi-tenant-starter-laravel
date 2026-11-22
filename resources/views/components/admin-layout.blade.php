@props(['title' => __('Dashboard')])

@php
    $user = auth()->user();

    $initials = \Illuminate\Support\Str::of($user->name ?? 'Super Admin')
        ->explode(' ')
        ->filter()
        ->take(2)
        ->map(fn (string $part): string => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($part, 0, 1)))
        ->implode('');

    $links = [
        ['label' => __('Dashboard'), 'icon' => 'grid', 'href' => route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard')],
        ['label' => __('Tenants'), 'icon' => 'box', 'href' => route('admin.tenants.index'), 'active' => request()->routeIs('admin.tenants.*')],
        ['label' => __('Activity Logs'), 'icon' => 'clipboard', 'href' => route('admin.activity-logs'), 'active' => request()->routeIs('admin.activity-logs')],
    ];

    $logoutUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute('central.logout', now()->addDay());
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="color-scheme: light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#020617">
        <title>{{ $title }} · {{ __('SaaS Super Admin') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="admin-shell min-h-screen bg-[#F5F7FA] text-slate-900">
        <a href="#admin-main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-slate-950 focus:ring-2 focus:ring-cyan-400">
            {{ __('Skip to content') }}
        </a>

        <div class="admin-shell-grid min-h-screen lg:grid lg:grid-cols-[16rem_minmax(0,1fr)]">
            <aside class="admin-sidebar hidden flex-col border-r border-slate-800 bg-slate-950 px-4 py-6 text-slate-300 lg:flex">
                <div class="admin-sidebar-header flex items-center gap-3 px-2">
                    <span class="admin-brand-icon flex h-10 w-10 items-center justify-center rounded-lg border border-cyan-400/40 bg-cyan-400/10 text-cyan-300" aria-hidden="true">
                        <x-icon name="grid" class="h-5 w-5" />
                    </span>
                    <div class="admin-brand-copy min-w-0">
                        <p class="admin-brand-name truncate text-sm font-bold tracking-tight text-white">{{ config('app.name', 'Orbit') }}</p>
                        <p class="admin-brand-kicker text-[10px] font-bold uppercase tracking-[0.16em] text-cyan-300">{{ __('Control Room') }}</p>
                    </div>
                </div>

                <div class="admin-role-badge mt-6 rounded-lg border border-cyan-400/25 bg-cyan-400/10 px-3 py-2 text-xs font-semibold text-cyan-200">
                    {{ __('SaaS Super Admin') }}
                </div>

                <nav aria-label="{{ __('Central administration') }}" class="admin-nav mt-8 flex-1">
                    <p class="admin-nav-label px-3 pb-2 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">{{ __('Manage') }}</p>
                    <ul class="admin-nav-list space-y-1">
                        @foreach ($links as $link)
                            <li>
                                <a
                                    href="{{ $link['href'] }}"
                                    @if ($link['active']) aria-current="page" @endif
                                    @class([
                                        'admin-nav-link flex items-center gap-3 rounded-lg border-l-2 px-3 py-2.5 text-sm font-semibold transition-colors duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-cyan-400',
                                        'is-active' => $link['active'],
                                    ])
                                >
                                    <x-icon :name="$link['icon']" class="h-4 w-4 shrink-0" />
                                    <span>{{ $link['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>

                <div class="admin-user-panel border-t border-slate-800 pt-4">
                    <div class="admin-user-row flex items-center gap-3 px-2">
                        <span class="admin-avatar flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-800 text-xs font-bold text-white">{{ $initials }}</span>
                        <div class="admin-user-copy min-w-0 flex-1">
                            <p class="admin-user-name truncate text-xs font-semibold text-white">{{ $user->name }}</p>
                            <p class="admin-user-email truncate text-[11px] text-slate-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ $logoutUrl }}" class="mt-4">
                        @csrf
                        <button type="submit" class="admin-logout flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-sm font-semibold text-slate-400 transition-colors hover:bg-slate-900 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-cyan-400">
                            <x-icon name="logout" class="h-4 w-4" />
                            {{ __('Log out') }}
                        </button>
                    </form>
                </div>
            </aside>

            <div class="min-w-0">
                <header class="admin-topbar sticky top-0 z-30 border-b border-slate-800 bg-slate-950 text-white lg:border-line lg:bg-white lg:text-slate-900">
                    <div class="admin-topbar-inner flex h-16 items-center gap-3 px-4 sm:px-6 lg:px-8">
                        <div class="admin-mobile-brand flex items-center gap-2 lg:hidden">
                            <span class="admin-mobile-brand-icon flex h-8 w-8 items-center justify-center rounded-lg bg-cyan-400/10 text-cyan-300">
                                <x-icon name="grid" class="h-4 w-4" />
                            </span>
                            <span class="admin-mobile-brand-name text-sm font-bold">{{ config('app.name', 'Orbit') }}</span>
                        </div>

                        <div class="admin-breadcrumb hidden items-center gap-2 text-xs font-semibold lg:flex">
                            <span class="admin-breadcrumb-root text-slate-500">{{ __('Central') }}</span>
                            <x-icon name="chevron-right" class="h-3.5 w-3.5 text-slate-400" />
                            <span class="admin-breadcrumb-current">{{ $title }}</span>
                        </div>

                        <span class="admin-topbar-badge ml-auto rounded-md border border-cyan-400/30 bg-cyan-400/10 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.14em] text-cyan-200 lg:border-cyan-600/30 lg:bg-cyan-50 lg:text-cyan-700">
                            {{ __('SaaS Super Admin') }}
                        </span>

                        <form method="POST" action="{{ $logoutUrl }}" class="lg:hidden">
                            @csrf
                            <button type="submit" class="admin-mobile-logout inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-700 text-slate-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-cyan-400" aria-label="{{ __('Log out') }}">
                                <x-icon name="logout" class="h-4 w-4" />
                            </button>
                        </form>
                    </div>

                    <nav aria-label="{{ __('Central administration (mobile)') }}" class="admin-mobile-nav flex gap-1 overflow-x-auto border-t border-slate-800 px-3 py-2 lg:hidden">
                        @foreach ($links as $link)
                            <a
                                href="{{ $link['href'] }}"
                                @if ($link['active']) aria-current="page" @endif
                                @class([
                                    'admin-mobile-nav-link whitespace-nowrap rounded-md px-3 py-1.5 text-xs font-semibold',
                                    'is-active' => $link['active'],
                                ])
                            >
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </header>

                <main id="admin-main" class="admin-main px-4 pb-16 pt-8 sm:px-6 lg:px-8">
                    <div class="admin-main-inner mx-auto w-full max-w-[90rem]">
                        @if (session('status'))
                            <div class="admin-status-message mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800" role="status">
                                {{ session('status') }}
                            </div>
                        @endif

                        @isset($header)
                            <div class="admin-page-header mb-8 border-b border-slate-200 pb-6">{{ $header }}</div>
                        @endisset

                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
