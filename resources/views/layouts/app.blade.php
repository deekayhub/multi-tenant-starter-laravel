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
    $workspaceName = tenant()?->name ?? config('app.name', 'Orbit');
    $logoutUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
        tenant() ? 'logout' : 'central.logout',
        now()->addDay(),
    );

    if (tenant()) {
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
        <meta name="theme-color" content="#F8FAFC">

        <title>{{ $title ? $title.' · ' : '' }}{{ config('app.name', 'Orbit') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-canvas text-foreground">
        <a href="#app-main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-lg focus:border focus:border-line focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-foreground focus:shadow-glass">
            {{ __('Skip to content') }}
        </a>

        <!-- Ambient light source -->
        <div class="pointer-events-none fixed -top-40 right-0 h-[28rem] w-[28rem] rounded-full bg-primary/10 blur-3xl" aria-hidden="true"></div>

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
                <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" @click="sidebarOpen = false" aria-hidden="true"></div>
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
                <!-- Top bar -->
                <header class="sticky top-0 z-30 border-b border-white/70 bg-white/70 backdrop-blur-xl">
                    <div class="flex h-16 items-center gap-3 px-4 sm:px-6 lg:px-8">
                        <button
                            type="button"
                            @click="sidebarCollapsed = ! sidebarCollapsed"
                            aria-controls="app-sidebar-rail"
                            x-bind:aria-expanded="String(! sidebarCollapsed)"
                            class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-line bg-white/80 text-foreground transition-colors hover:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary lg:inline-flex"
                        >
                            <x-icon name="chevron-right" class="h-5 w-5 transition-transform duration-150" x-bind:class="sidebarCollapsed ? 'rotate-180' : ''" />
                            <span class="sr-only">{{ __('Toggle sidebar') }}</span>
                        </button>

                        <button
                            type="button"
                            @click="sidebarOpen = true"
                            aria-controls="app-sidebar-panel"
                            :aria-expanded="sidebarOpen"
                            class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-line bg-white/80 text-foreground transition-colors hover:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary lg:hidden"
                        >
                            <x-icon name="menu" class="h-5 w-5" />
                            <span class="sr-only">{{ __('Open menu') }}</span>
                        </button>

                        <div class="flex min-w-0 items-center gap-2 text-xs font-semibold">
                            <span class="hidden truncate text-muted-foreground sm:inline">{{ $workspaceName }}</span>
                            <span class="hidden text-line sm:inline" aria-hidden="true">/</span>
                            <span class="truncate text-foreground">{{ $section }}</span>
                        </div>

                        <div class="ml-auto flex items-center gap-1.5 sm:gap-2">
                            <x-dropdown align="right" width="56">
                                <x-slot name="trigger">
                                    <button type="button" :aria-expanded="open" class="flex items-center gap-2 rounded-lg p-1 transition-colors hover:bg-white/80 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary sm:pr-2">
                                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-secondary to-primary text-[11px] font-bold text-white" aria-hidden="true">{{ $initials }}</span>
                                        <span class="hidden max-w-[8rem] truncate text-sm font-semibold text-foreground sm:block">{{ auth()->user()->name }}</span>
                                        <x-icon name="chevron-down" class="hidden h-3.5 w-3.5 text-muted-foreground sm:block" />
                                        <span class="sr-only sm:hidden">{{ __('Account menu') }}</span>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="border-b border-line px-3 py-3">
                                        <p class="truncate text-sm font-bold text-foreground">{{ auth()->user()->name }}</p>
                                        <p class="truncate text-[11px] font-medium text-muted-foreground">{{ auth()->user()->email }}</p>
                                    </div>

                                    <x-dropdown-link :href="$settingsRoute">{{ __('Settings') }}</x-dropdown-link>

                                    <div class="my-1 border-t border-line"></div>

                                    <form method="POST" action="{{ $logoutUrl }}">
                                        @csrf
                                        <x-dropdown-link :href="$logoutUrl" onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{ __('Log out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </header>

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
