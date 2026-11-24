@props(['title' => __('Settings'), 'description' => null])

@php
    $user = auth()->user();
    $isTenant = tenant() !== null;

    $accountLinks = [
        [
            'label' => __('Profile'),
            'icon' => 'user',
            'href' => route('profile.edit'),
            'active' => request()->routeIs('profile.*'),
        ],
    ];

    $workspaceLinks = [];

    if ($isTenant && $user?->can('workspace.view')) {
        $workspaceLinks[] = [
            'label' => __('Workspace'),
            'icon' => 'storefront',
            'href' => route('settings.workspace.edit'),
            'active' => request()->routeIs('settings.workspace.*'),
        ];
    }

    if ($isTenant && $user?->can('users.view')) {
        $workspaceLinks[] = [
            'label' => __('Team Members'),
            'icon' => 'users',
            'href' => route('settings.members.index'),
            'active' => request()->routeIs('settings.members.*'),
        ];
    }

    if ($isTenant && $user?->can('roles.view')) {
        $workspaceLinks[] = [
            'label' => __('Roles & Permissions'),
            'icon' => 'cog',
            'href' => route('settings.roles.index'),
            'active' => request()->routeIs('settings.roles.*'),
        ];
    }

    $sections = array_filter([
        __('Account') => $accountLinks,
        __('Workspace') => $workspaceLinks,
    ]);
@endphp

<x-app-layout :title="$title">
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="eyebrow">{{ __('Settings') }}</p>
                <h2 class="mt-2 text-2xl font-semibold leading-tight tracking-tight text-foreground sm:text-3xl">{{ $title }}</h2>
                @if ($description)
                    <p class="mt-1.5 max-w-2xl text-sm leading-6 text-muted-foreground">{{ $description }}</p>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="grid gap-5 lg:grid-cols-[12rem_minmax(0,1fr)] xl:gap-6">
        <nav aria-label="{{ __('Settings sections') }}" class="lg:sticky lg:top-24 lg:self-start">
            <div class="rounded-xl border border-line bg-white p-2">
                <div class="flex flex-col gap-3">
                    @foreach ($sections as $label => $links)
                        <div>
                            <p class="px-2.5 pb-1.5 text-[10px] font-bold uppercase tracking-[0.14em] text-muted-foreground">{{ $label }}</p>
                            <ul class="flex gap-1 overflow-x-auto lg:flex-col lg:overflow-visible">
                                @foreach ($links as $link)
                                    <li class="shrink-0">
                                        <a
                                            href="{{ $link['href'] }}"
                                            @if ($link['active']) aria-current="page" @endif
                                            @class([
                                                'flex items-center gap-2 whitespace-nowrap rounded-lg px-2.5 py-2 text-[13px] font-semibold transition-colors duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary',
                                                'bg-zinc-100 text-zinc-900' => $link['active'],
                                                'text-muted-foreground hover:bg-muted/70 hover:text-foreground' => ! $link['active'],
                                            ])
                                        >
                                            <x-icon :name="$link['icon']" class="h-4 w-4 shrink-0" />
                                            <span>{{ $link['label'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </nav>

        <div class="min-w-0 max-w-4xl space-y-5">
            {{ $slot }}
        </div>
    </div>
</x-app-layout>
