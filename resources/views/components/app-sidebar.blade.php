@props(['closable' => false, 'collapsible' => false])

@php
    $isTenant = tenant() !== null;
    $user = auth()->user();
    $dashboardRoute = $isTenant ? route('tenant.dashboard') : route('dashboard');

    $workspaceItems = [
        ['label' => __('Overview'), 'icon' => 'grid', 'href' => $dashboardRoute, 'active' => request()->routeIs('dashboard', 'tenant.dashboard')],
    ];

    if (! $isTenant || $user?->can('activity-logs.view')) {
        $workspaceItems[] = [
            'label' => __('Activity'),
            'icon' => 'chart',
            'href' => $isTenant ? route('tenant.activity.index') : route('activity.index'),
            'active' => request()->routeIs('activity.index', 'tenant.activity.index'),
        ];
    }

    $settingsItems = [
        ['label' => __('Profile'), 'icon' => 'user', 'href' => route('profile.edit'), 'active' => request()->routeIs('profile.*')],
    ];

    if ($isTenant && $user?->can('workspace.view')) {
        $settingsItems[] = [
            'label' => __('Workspace'),
            'icon' => 'storefront',
            'href' => route('settings.workspace.edit'),
            'active' => request()->routeIs('settings.workspace.*'),
        ];
    }

    if ($isTenant && $user?->can('users.view')) {
        $settingsItems[] = [
            'label' => __('Team Members'),
            'icon' => 'users',
            'href' => route('settings.members.index'),
            'active' => request()->routeIs('settings.members.*'),
        ];
    }

    if ($isTenant && $user?->can('roles.view')) {
        $settingsItems[] = [
            'label' => __('Roles & Permissions'),
            'icon' => 'cog',
            'href' => route('settings.roles.index'),
            'active' => request()->routeIs('settings.roles.*'),
        ];
    }

    $groups = [
        [
            'label' => __('Workspace'),
            'items' => $workspaceItems,
        ],
        [
            'label' => __('Account'),
            'items' => [
                [
                    'label' => __('Settings'),
                    'icon' => 'cog',
                    'active' => request()->routeIs('settings.*', 'profile.*'),
                    'children' => $settingsItems,
                ],
            ],
        ],
    ];
@endphp

<div class="relative flex h-full flex-col overflow-hidden bg-slate-900/95 text-white backdrop-blur-2xl">
    <div class="pointer-events-none absolute inset-0 bg-gradient-to-b from-primary/25 via-transparent to-secondary/10" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -left-24 top-1/3 h-72 w-72 rounded-full bg-primary/20 blur-3xl" aria-hidden="true"></div>

    <!-- Brand -->
    <div
        class="relative flex h-16 shrink-0 items-center gap-3 border-b border-white/10 px-4"
        @if ($collapsible) x-bind:class="sidebarCollapsed ? 'justify-center px-0' : ''" @endif
    >
        <a href="{{ $dashboardRoute }}" class="inline-flex items-center gap-2.5 rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary" aria-label="{{ __('Orbit dashboard') }}">
            <x-brand-mark class="h-9 w-9" />
            <span class="text-lg font-extrabold tracking-tight" @if ($collapsible) x-show="! sidebarCollapsed" @endif>orbit</span>
        </a>

        @if ($closable)
            <button
                type="button"
                @click="$dispatch('close-sidebar')"
                class="ml-auto inline-flex h-9 w-9 items-center justify-center rounded-lg text-white/80 transition-colors hover:bg-white/10 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                aria-label="{{ __('Close menu') }}"
            >
                <x-icon name="x" class="h-4 w-4" />
            </button>
        @endif
    </div>

    <!-- Navigation -->
    <nav
        class="sidebar-scroll relative flex-1 overflow-y-auto py-6 {{ $collapsible ? '' : 'px-3' }}"
        @if ($collapsible) x-bind:class="sidebarCollapsed ? 'px-2' : 'px-3'" @endif
        aria-label="{{ __('Main navigation') }}"
    >
        <div class="space-y-7">
            @foreach ($groups as $group)
                <div>
                    <p
                        class="px-3 text-[10px] font-bold uppercase tracking-[0.16em] text-white/75"
                        @if ($collapsible) x-show="! sidebarCollapsed" @endif
                    >{{ $group['label'] }}</p>
                    <ul class="mt-3 space-y-1">
                        @foreach ($group['items'] as $item)
                            @if (isset($item['children']))
                                @php
                                    $submenuId = $collapsible ? 'settings-submenu-rail' : 'settings-submenu-panel';
                                @endphp
                                <li
                                    x-data="{ settingsOpen: @json($item['active']) }"
                                    @if ($collapsible)
                                        @keydown.escape.window="settingsOpen = false"
                                    @endif
                                >
                                    <button
                                        type="button"
                                        @click="settingsOpen = ! settingsOpen"
                                        :aria-expanded="settingsOpen ? 'true' : 'false'"
                                        aria-controls="{{ $submenuId }}"
                                        @if ($collapsible)
                                            data-label="{{ $item['label'] }}"
                                            x-bind:class="sidebarCollapsed ? 'justify-center px-0' : ''"
                                            x-bind:title="sidebarCollapsed ? $el.dataset.label : null"
                                            x-bind:aria-label="sidebarCollapsed ? $el.dataset.label : null"
                                        @endif
                                        @class([
                                            'group flex w-full items-center gap-3 rounded-lg border-l-2 px-3 py-2.5 text-sm font-medium transition-all duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary',
                                            'border-primary bg-primary/25 text-white shadow-sm' => $item['active'],
                                            'border-transparent text-white/80 hover:border-white/30 hover:bg-white/10 hover:text-white' => ! $item['active'],
                                        ])
                                    >
                                        <x-icon :name="$item['icon']" class="h-[18px] w-[18px] shrink-0 {{ $item['active'] ? 'text-white' : 'text-white/75 group-hover:text-white' }}" />
                                        <span class="flex-1 truncate text-left" @if ($collapsible) x-show="! sidebarCollapsed" @endif>{{ $item['label'] }}</span>
                                        <x-icon
                                            name="chevron-down"
                                            class="h-3.5 w-3.5 shrink-0 text-white/70 transition-transform duration-150"
                                            @if ($collapsible)
                                                x-show="! sidebarCollapsed"
                                                x-bind:class="settingsOpen ? 'rotate-180' : ''"
                                            @else
                                                x-bind:class="settingsOpen ? 'rotate-180' : ''"
                                            @endif
                                            x-cloak
                                        />
                                    </button>

                                    <ul
                                        id="{{ $submenuId }}"
                                        class="mt-1 space-y-0.5 border-l border-white/15 pl-4"
                                        x-show="settingsOpen"
                                        x-cloak
                                        x-transition:enter="transition ease-out duration-150"
                                        x-transition:enter-start="opacity-0 -translate-y-1"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        @if ($collapsible) x-bind:class="sidebarCollapsed ? 'hidden' : ''" @endif
                                    >
                                        @foreach ($item['children'] as $child)
                                            <li>
                                                <a
                                                    href="{{ $child['href'] }}"
                                                    @if ($child['active']) aria-current="page" @endif
                                                    @class([
                                                        'flex items-center gap-2 rounded-md px-2 py-1.5 text-[13px] font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary',
                                                        'bg-white/15 text-white' => $child['active'],
                                                        'text-white/70 hover:bg-white/10 hover:text-white' => ! $child['active'],
                                                    ])
                                                >
                                                    <x-icon :name="$child['icon']" class="h-3.5 w-3.5 shrink-0" />
                                                    <span class="truncate">{{ $child['label'] }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li>
                                    <a
                                        href="{{ $item['href'] }}"
                                        @if ($item['active']) aria-current="page" @endif
                                        @if ($collapsible)
                                            data-label="{{ $item['label'] }}"
                                            x-bind:class="sidebarCollapsed ? 'justify-center px-0' : ''"
                                            x-bind:title="sidebarCollapsed ? $el.dataset.label : null"
                                            x-bind:aria-label="sidebarCollapsed ? $el.dataset.label : null"
                                        @endif
                                        @class([
                                            'group flex items-center gap-3 rounded-lg border-l-2 px-3 py-2.5 text-sm font-medium transition-all duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary',
                                            'border-primary bg-primary/25 text-white shadow-sm' => $item['active'],
                                            'border-transparent text-white/80 hover:border-white/30 hover:bg-white/10 hover:text-white' => ! $item['active'],
                                        ])
                                    >
                                        <x-icon :name="$item['icon']" class="h-[18px] w-[18px] shrink-0 {{ $item['active'] ? 'text-white' : 'text-white/75 group-hover:text-white' }}" />
                                        <span class="flex-1 truncate" @if ($collapsible) x-show="! sidebarCollapsed" @endif>{{ $item['label'] }}</span>
                                        @isset($item['badge'])
                                            <span class="rounded-md border border-white/20 bg-white/10 px-1.5 py-0.5 text-[10px] font-bold text-white/85" @if ($collapsible) x-show="! sidebarCollapsed" @endif>{{ $item['badge'] }}</span>
                                        @endisset
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </nav>

</div>
