@props([
    'links' => [],
    'isSuperAdmin' => false,
])

<aside
    id="admin-sidebar-rail"
    class="admin-sidebar hidden flex-col py-6 text-zinc-300 lg:flex"
    x-bind:class="sidebarCollapsed ? 'lg:px-2' : 'lg:px-4'"
>
    <div
        class="flex items-center gap-3 px-2"
        x-bind:class="sidebarCollapsed ? 'justify-center px-0' : ''"
    >
        <span class="admin-brand-icon flex h-10 w-10 shrink-0 items-center justify-center rounded-lg" aria-hidden="true">
            <x-icon name="grid" class="h-5 w-5" />
        </span>
        <div class="min-w-0" x-show="! sidebarCollapsed" x-cloak>
            <p class="admin-brand-name truncate text-sm font-bold tracking-tight">{{ config('app.name', 'Orbit') }}</p>
            <p class="admin-brand-kicker text-[10px] font-bold uppercase tracking-[0.16em]">{{ __('Control Room') }}</p>
        </div>
    </div>

    @if ($isSuperAdmin)
        <div class="admin-role-badge rounded-lg px-3 py-2 text-xs font-semibold" x-show="! sidebarCollapsed" x-cloak>
            {{ __('SaaS Super Admin') }}
        </div>
    @endif

    <nav aria-label="{{ __('Central administration') }}" class="admin-nav mt-8 flex-1">
        <p class="admin-nav-label px-3 pb-2 text-[10px] font-bold uppercase tracking-[0.18em]" x-show="! sidebarCollapsed" x-cloak>{{ __('Manage') }}</p>
        <ul class="admin-nav-list">
            @foreach ($links as $link)
                <li>
                    <a
                        href="{{ $link['href'] }}"
                        @if ($link['active']) aria-current="page" @endif
                        data-label="{{ $link['label'] }}"
                        x-bind:class="sidebarCollapsed ? 'justify-center px-0' : ''"
                        x-bind:title="sidebarCollapsed ? $el.dataset.label : null"
                        x-bind:aria-label="sidebarCollapsed ? $el.dataset.label : null"
                        @class([
                            'admin-nav-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold transition-colors duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-400',
                            'is-active' => $link['active'],
                        ])
                    >
                        <x-icon :name="$link['icon']" class="h-4 w-4 shrink-0" />
                        <span x-show="! sidebarCollapsed" x-cloak>{{ $link['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
</aside>
