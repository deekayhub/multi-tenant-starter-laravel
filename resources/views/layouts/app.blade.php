@props(['title' => null])

@php
    $path = trim(request()->path(), '/');
    $section = $path === '' ? __('Overview') : \Illuminate\Support\Str::headline(explode('/', $path)[0]);

    $initials = \Illuminate\Support\Str::of(auth()->user()->name ?? 'Starter User')
        ->explode(' ')
        ->filter()
        ->take(2)
        ->map(fn (string $part): string => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($part, 0, 1)))
        ->implode('');

    $settingsRoute = route('profile.edit');
    $isTenant = tenant() !== null;
    $workspaceName = tenant()?->name ?? config('app.name', 'Orbit');
    $logoutUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
        $isTenant ? 'logout' : 'central.logout',
        now()->addDay(),
    );

    if ($isTenant) {
        if (auth()->user()?->can('workspace.view')) {
            $settingsRoute = route('settings.workspace.edit');
        } elseif (auth()->user()?->can('users.view')) {
            $settingsRoute = route('settings.members.index');
        } elseif (auth()->user()?->can('roles.view')) {
            $settingsRoute = route('settings.roles.index');
        }
    }
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="color-scheme: light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#FAFAFA">

        <title>{{ $title ? $title.' · ' : '' }}{{ config('app.name', 'Orbit') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-canvas text-foreground">
        <a href="#app-main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-lg focus:border focus:border-line focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-foreground focus:shadow-glass">
            {{ __('Skip to content') }}
        </a>

        <div
            x-data="{
                sidebarOpen: false,
                sidebarCollapsed: localStorage.getItem('orbit:sidebar-collapsed') === '1',
                init() {
                    this.$watch('sidebarCollapsed', value => localStorage.setItem('orbit:sidebar-collapsed', value ? '1' : '0'));
                },
            }"
            @keydown.escape.window="sidebarOpen = false"
            @close-sidebar.window="sidebarOpen = false"
        >
            <!-- Desktop rail -->
            <aside
                id="app-sidebar-rail"
                class="fixed inset-y-0 left-0 z-40 hidden lg:block"
                x-bind:class="sidebarCollapsed ? 'lg:w-[4.5rem]' : 'lg:w-[17rem]'"
            >
                <x-app-sidebar :collapsible="true" />
            </aside>

            <!-- Mobile drawer -->
            <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-50 lg:hidden">
                <div class="absolute inset-0 bg-zinc-950/50" @click="sidebarOpen = false" aria-hidden="true"></div>
                <div
                    id="app-sidebar-panel"
                    role="dialog"
                    aria-modal="true"
                    aria-label="{{ __('Navigation menu') }}"
                    class="absolute inset-y-0 left-0 flex w-[17rem] max-w-[85vw] flex-col shadow-glass-lg"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="-translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="-translate-x-full"
                >
                    <x-app-sidebar :closable="true" />
                </div>
            </div>

            <div class="relative" x-bind:class="sidebarCollapsed ? 'lg:pl-[4.5rem]' : 'lg:pl-[17rem]'">
                <x-app-header
                    :workspace-name="$workspaceName"
                    :section="$section"
                    :initials="$initials"
                    :settings-route="$settingsRoute"
                    :logout-url="$logoutUrl"
                    :is-tenant="$isTenant"
                />

                <!-- Page content -->
                <main id="app-main" class="relative px-4 pb-16 pt-8 sm:px-6 lg:px-8">
                    <div class="mx-auto w-full max-w-[90rem]">
                        @isset($header)
                            <div class="mb-8 border-b border-line pb-6">{{ $header }}</div>
                        @endisset

                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
