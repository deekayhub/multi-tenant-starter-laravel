@props(['title' => __('Dashboard')])

@php
    $user = auth()->user();
    $isSuperAdmin = (bool) ($user?->is_super_admin);

    $initials = \Illuminate\Support\Str::of($user->name ?? 'Super Admin')
        ->explode(' ')
        ->filter()
        ->take(2)
        ->map(fn (string $part): string => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($part, 0, 1)))
        ->implode('');

    $links = $isSuperAdmin
        ? [
            ['label' => __('Dashboard'), 'icon' => 'grid', 'href' => route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard')],
            ['label' => __('Tenants'), 'icon' => 'box', 'href' => route('admin.tenants.index'), 'active' => request()->routeIs('admin.tenants.*')],
            ['label' => __('Activity Logs'), 'icon' => 'clipboard', 'href' => route('admin.activity-logs'), 'active' => request()->routeIs('admin.activity-logs')],
        ]
        : [
            ['label' => __('Dashboard'), 'icon' => 'grid', 'href' => route('dashboard'), 'active' => request()->routeIs('dashboard')],
            ['label' => __('Activity'), 'icon' => 'clipboard', 'href' => route('activity.index'), 'active' => request()->routeIs('activity.index')],
        ];

    $links[] = ['label' => __('Profile'), 'icon' => 'user', 'href' => route('central.profile.edit'), 'active' => request()->routeIs('central.profile.*')];

    $logoutUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute('central.logout', now()->addDay());
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="color-scheme: light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#09090B">
        <title>{{ $title }} · {{ __('SaaS Super Admin') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="admin-shell min-h-screen bg-canvas text-zinc-900">
        <a href="#admin-main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-zinc-950 focus:ring-2 focus:ring-zinc-400">
            {{ __('Skip to content') }}
        </a>

        <div
            x-data="{
                sidebarCollapsed: localStorage.getItem('orbit:admin-sidebar-collapsed') === '1',
                init() {
                    this.$watch('sidebarCollapsed', value => localStorage.setItem('orbit:admin-sidebar-collapsed', value ? '1' : '0'));
                },
            }"
        >
            <div
                class="admin-shell-grid min-h-screen lg:transition-all lg:duration-200"
                x-bind:class="sidebarCollapsed ? 'lg:grid-cols-[4.5rem_minmax(0,1fr)]' : 'lg:grid-cols-[16rem_minmax(0,1fr)]'"
            >
                <x-admin-sidebar :links="$links" :is-super-admin="$isSuperAdmin" />

                <div class="min-w-0">
                    <x-admin-header
                        :title="$title"
                        :links="$links"
                        :initials="$initials"
                        :user-name="$user->name"
                        :logout-url="$logoutUrl"
                        :is-super-admin="$isSuperAdmin"
                    />

                    <main id="admin-main" class="admin-main px-4 pb-16 pt-8 sm:px-6 lg:px-8">
                        <div class="admin-main-inner mx-auto w-full max-w-[90rem]">
                            @if (session('status'))
                                <div class="admin-status-message mb-6 rounded-lg px-4 py-3 text-sm font-semibold" role="status">
                                    {{ session('status') }}
                                </div>
                            @endif

                            @isset($header)
                                <div class="admin-page-header mb-8 pb-6">{{ $header }}</div>
                            @endisset

                            {{ $slot }}
                        </div>
                    </main>
                </div>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
