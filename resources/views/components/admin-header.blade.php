@props([
    'title' => __('Dashboard'),
    'links' => [],
    'initials' => '',
    'userName' => '',
    'logoutUrl',
    'isSuperAdmin' => false,
])

<header class="admin-topbar sticky top-0 z-30 border-b lg:border-line">
    <div class="admin-topbar-inner flex h-16 items-center gap-3 px-4 sm:px-6 lg:px-8">
        <button
            type="button"
            @click="sidebarCollapsed = ! sidebarCollapsed"
            aria-controls="admin-sidebar-rail"
            x-bind:aria-expanded="String(! sidebarCollapsed)"
            class="admin-collapse hidden h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-zinc-700 text-zinc-300 transition-colors hover:bg-zinc-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-400 lg:inline-flex"
        >
            <x-icon name="chevron-right" class="h-5 w-5 transition-transform duration-150" x-bind:class="sidebarCollapsed ? 'rotate-180' : ''" />
            <span class="sr-only">{{ __('Toggle sidebar') }}</span>
        </button>

        <div class="admin-mobile-brand flex items-center gap-2 lg:hidden">
            <span class="admin-mobile-brand-icon flex h-8 w-8 items-center justify-center rounded-lg">
                <x-icon name="grid" class="h-4 w-4" />
            </span>
            <span class="admin-mobile-brand-name text-sm font-bold">{{ config('app.name', 'Orbit') }}</span>
        </div>

        <div class="admin-breadcrumb hidden items-center gap-2 text-xs font-semibold lg:flex">
            <span class="admin-breadcrumb-root">{{ __('Central') }}</span>
            <x-icon name="chevron-right" class="h-3.5 w-3.5" />
            <span class="admin-breadcrumb-current">{{ $title }}</span>
        </div>

        <div class="ml-auto flex items-center gap-2">
            @if ($isSuperAdmin)
                <span class="admin-topbar-badge hidden rounded-md px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.14em] sm:inline">
                    {{ __('SaaS Super Admin') }}
                </span>
            @endif

            <form method="POST" action="{{ $logoutUrl }}" class="lg:hidden">
                @csrf
                <button type="submit" class="admin-mobile-logout inline-flex h-9 w-9 items-center justify-center rounded-lg" aria-label="{{ __('Log out') }}">
                    <x-icon name="logout" class="h-4 w-4" />
                </button>
            </form>

            <x-dropdown align="right" width="52">
                <x-slot name="trigger">
                    <button type="button" :aria-expanded="open" class="hidden items-center gap-2 rounded-lg p-1 transition-colors hover:bg-zinc-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-400 lg:flex">
                        <span class="admin-avatar flex h-9 w-9 items-center justify-center rounded-lg text-[11px] font-bold" aria-hidden="true">{{ $initials }}</span>
                        <span class="max-w-[8rem] truncate text-sm font-semibold">{{ $userName }}</span>
                        <x-icon name="chevron-down" class="h-3.5 w-3.5 text-zinc-500" />
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="border-b border-line px-3 py-3">
                        <p class="truncate text-sm font-bold text-zinc-900">{{ $userName }}</p>
                    </div>

                    <x-dropdown-link :href="route('central.profile.edit')" @class(['bg-muted' => request()->routeIs('central.profile.*')])>{{ __('Profile') }}</x-dropdown-link>

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

    <nav aria-label="{{ __('Central administration (mobile)') }}" class="admin-mobile-nav flex gap-1 overflow-x-auto px-3 py-2 lg:hidden">
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
