@props(['closable' => false])

@php
    $initials = \Illuminate\Support\Str::of(auth()->user()->name ?? 'Starter User')
        ->explode(' ')
        ->filter()
        ->take(2)
        ->map(fn (string $part): string => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($part, 0, 1)))
        ->implode('');

    $groups = [
        [
            'label' => __('Workspace'),
            'items' => [
                ['label' => __('Overview'), 'icon' => 'grid', 'href' => route('dashboard'), 'active' => request()->routeIs('dashboard')],
                ['label' => __('Projects'), 'icon' => 'box', 'href' => '#', 'active' => false, 'badge' => '4'],
                ['label' => __('Tasks'), 'icon' => 'clipboard', 'href' => '#', 'active' => false, 'badge' => '12'],
                ['label' => __('Activity'), 'icon' => 'chart', 'href' => '#', 'active' => false],
            ],
        ],
        [
            'label' => __('Tenancy'),
            'items' => [
                ['label' => __('Tenants'), 'icon' => 'storefront', 'href' => '#', 'active' => false, 'badge' => '3'],
                ['label' => __('Members'), 'icon' => 'users', 'href' => '#', 'active' => false],
                ['label' => __('Billing'), 'icon' => 'wallet', 'href' => '#', 'active' => false],
                ['label' => __('Integrations'), 'icon' => 'plug', 'href' => '#', 'active' => false],
            ],
        ],
        [
            'label' => __('Account'),
            'items' => [
                ['label' => __('Settings'), 'icon' => 'cog', 'href' => '#', 'active' => false],
                ['label' => __('Profile'), 'icon' => 'user', 'href' => route('profile.edit'), 'active' => request()->routeIs('profile.*')],
            ],
        ],
    ];
@endphp

<div class="relative flex h-full flex-col overflow-hidden bg-slate-900/95 text-white backdrop-blur-2xl">
    <div class="pointer-events-none absolute inset-0 bg-gradient-to-b from-primary/25 via-transparent to-secondary/10" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -left-24 top-1/3 h-72 w-72 rounded-full bg-primary/20 blur-3xl" aria-hidden="true"></div>

    <!-- Brand -->
    <div class="relative flex h-16 shrink-0 items-center gap-3 border-b border-white/10 px-4">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2.5 rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary" aria-label="{{ __('Starter kit dashboard') }}">
            <x-brand-mark class="h-9 w-9" />
            <span class="text-lg font-extrabold tracking-tight">orbit</span>
            <span class="rounded-md border border-white/20 bg-white/10 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-[0.14em] text-white/80">Kit</span>
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
    <nav class="relative flex-1 overflow-y-auto px-3 py-6" aria-label="{{ __('Main navigation') }}">
        <div class="space-y-7">
            @foreach ($groups as $group)
                <div>
                    <p class="px-3 text-[10px] font-bold uppercase tracking-[0.16em] text-white/75">{{ $group['label'] }}</p>
                    <ul class="mt-3 space-y-1">
                        @foreach ($group['items'] as $item)
                            <li>
                                <a
                                    href="{{ $item['href'] }}"
                                    @if ($item['active']) aria-current="page" @endif
                                    @class([
                                        'group flex items-center gap-3 rounded-lg border-l-2 px-3 py-2.5 text-sm font-medium transition-all duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary',
                                        'border-primary bg-primary/25 text-white shadow-sm' => $item['active'],
                                        'border-transparent text-white/80 hover:border-white/30 hover:bg-white/10 hover:text-white' => ! $item['active'],
                                    ])
                                >
                                    <x-icon :name="$item['icon']" class="h-[18px] w-[18px] shrink-0 {{ $item['active'] ? 'text-white' : 'text-white/75 group-hover:text-white' }}" />
                                    <span class="flex-1 truncate">{{ $item['label'] }}</span>
                                    @isset($item['badge'])
                                        <span class="rounded-md border border-white/20 bg-white/10 px-1.5 py-0.5 text-[10px] font-bold text-white/85">{{ $item['badge'] }}</span>
                                    @endisset
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            <div class="rounded-xl border border-white/20 bg-white/10 p-4 backdrop-blur-md">
                <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-white/80">{{ __('Starter note') }}</p>
                <p class="mt-2 text-xs leading-5 text-white/85">{{ __('Swap these screens and placeholders for your own product.') }}</p>
                <a href="#" class="mt-3 inline-flex items-center gap-1.5 rounded text-xs font-bold text-white transition-colors hover:text-[#BFDBFE] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                    {{ __('View checklist') }}
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Account -->
    <div class="relative shrink-0 border-t border-white/10 p-3">
        <div class="flex items-center gap-3 rounded-xl p-1.5">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white/15 text-[11px] font-bold" aria-hidden="true">{{ $initials }}</span>
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-semibold">{{ auth()->user()->name }}</p>
                <p class="truncate text-[10px] font-medium text-white/75">{{ auth()->user()->email }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-white/75 transition-colors hover:bg-white/10 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary" aria-label="{{ __('Log out') }}" title="{{ __('Log out') }}">
                    <x-icon name="logout" class="h-4 w-4" />
                </button>
            </form>
        </div>
    </div>
</div>
