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

<div class="app-sidebar-shell relative flex h-full flex-col overflow-hidden text-zinc-100">
    <div class="app-sidebar-glow" aria-hidden="true"></div>

    <div
        class="relative flex h-16 shrink-0 items-center gap-3 px-4"
        @if ($collapsible) x-bind:class="sidebarCollapsed ? 'justify-center px-0' : ''" @endif
    >
        <a href="{{ $dashboardRoute }}" class="inline-flex items-center gap-2.5 rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/60" aria-label="{{ __('Orbit dashboard') }}">
            <x-brand-mark class="h-9 w-9" />
            <span class="text-lg font-extrabold tracking-tight" @if ($collapsible) x-show="! sidebarCollapsed" @endif>orbit</span>
        </a>

        @if ($closable)
            <button
                type="button"
                @click="$dispatch('close-sidebar')"
                class="ml-auto inline-flex h-9 w-9 items-center justify-center rounded-lg text-white/80 transition-colors hover:bg-white/10 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/60"
                aria-label="{{ __('Close menu') }}"
            >
                <x-icon name="x" class="h-4 w-4" />
            </button>
        @endif
    </div>

    <nav
        class="sidebar-scroll relative flex-1 overflow-y-auto px-3 py-6"
        aria-label="{{ __('Main navigation') }}"
    >
        <div class="flex flex-col gap-6">
            @foreach ($groups as $group)
                <div>
                    <p
                        class="px-3 text-[10px] font-bold uppercase tracking-[0.16em] text-white/75"
                        @if ($collapsible) x-show="! sidebarCollapsed" @endif
                    >{{ $group['label'] }}</p>
                    <ul class="mt-3 flex flex-col gap-1">
                        @foreach ($group['items'] as $item)
                            @if (isset($item['children']))
                                <x-sidebar-submenu
                                    :label="$item['label']"
                                    :icon="$item['icon']"
                                    :children="$item['children']"
                                    :active="$item['active']"
                                    :collapsible="$collapsible"
                                    :id="$collapsible ? 'settings-submenu-rail' : 'settings-submenu-panel'"
                                />
                            @else
                                <x-sidebar-menu-item
                                    :label="$item['label']"
                                    :icon="$item['icon']"
                                    :href="$item['href']"
                                    :active="$item['active']"
                                    :collapsible="$collapsible"
                                />
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </nav>
</div>
