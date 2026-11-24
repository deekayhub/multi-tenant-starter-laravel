@props([
    'workspaceName',
    'section',
    'initials',
    'settingsRoute',
    'logoutUrl',
    'isTenant' => false,
])

@php
    $context = $isTenant ? __('Tenant Dashboard') : __('Account Dashboard');
@endphp

<header class="sticky top-0 z-30 border-b border-white/70 bg-white">
    <div class="flex h-16 items-center gap-3 px-4 sm:px-6 lg:px-8">
        <button
            type="button"
            @click="sidebarCollapsed = ! sidebarCollapsed"
            aria-controls="app-sidebar-rail"
            x-bind:aria-expanded="String(! sidebarCollapsed)"
            class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-line bg-white text-foreground transition-colors hover:bg-muted focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary lg:inline-flex"
        >
            <x-icon name="chevron-right" class="h-5 w-5 transition-transform duration-150" x-bind:class="sidebarCollapsed ? 'rotate-180' : ''" />
            <span class="sr-only">{{ __('Toggle sidebar') }}</span>
        </button>

        <button
            type="button"
            @click="sidebarOpen = true"
            aria-controls="app-sidebar-panel"
            :aria-expanded="sidebarOpen"
            class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-line bg-white text-foreground transition-colors hover:bg-muted focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary lg:hidden"
        >
            <x-icon name="menu" class="h-5 w-5" />
            <span class="sr-only">{{ __('Open menu') }}</span>
        </button>

        <div class="flex min-w-0 items-center gap-2 text-xs font-semibold">
            <span class="hidden shrink-0 rounded-md bg-zinc-100 px-2 py-1 text-[10px] font-bold uppercase tracking-[0.12em] text-zinc-700 sm:inline">{{ $context }}</span>
            <span class="hidden truncate text-muted-foreground md:inline">{{ $workspaceName }}</span>
            <span class="hidden text-line md:inline" aria-hidden="true">/</span>
            <span class="truncate text-foreground">{{ $section }}</span>
        </div>

        <div class="ml-auto flex items-center gap-1.5 sm:gap-2">
            <x-dropdown align="right" width="56">
                <x-slot name="trigger">
                    <button type="button" :aria-expanded="open" class="flex items-center gap-2 rounded-lg p-1 transition-colors hover:bg-muted focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary sm:pr-2">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-zinc-900 text-[11px] font-bold text-white" aria-hidden="true">{{ $initials }}</span>
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
