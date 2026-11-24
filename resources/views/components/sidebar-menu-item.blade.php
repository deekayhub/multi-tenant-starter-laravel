@props([
    'label',
    'icon',
    'href',
    'active' => false,
    'collapsible' => false,
])

<li>
    <a
        href="{{ $href }}"
        @if ($active) aria-current="page" @endif
        @if ($collapsible)
            data-label="{{ $label }}"
            x-bind:class="sidebarCollapsed ? 'justify-center px-0' : ''"
            x-bind:title="sidebarCollapsed ? $el.dataset.label : null"
            x-bind:aria-label="sidebarCollapsed ? $el.dataset.label : null"
        @endif
        @class([
            'group flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/60',
            'app-sidebar-active' => $active,
            'text-zinc-400 hover:bg-white/5 hover:text-zinc-100' => ! $active,
        ])
    >
        <x-icon :name="$icon" class="h-[18px] w-[18px] shrink-0 {{ $active ? 'text-white' : 'text-white/75 group-hover:text-white' }}" />
        <span class="flex-1 truncate text-left" @if ($collapsible) x-show="! sidebarCollapsed" @endif>{{ $label }}</span>
    </a>
</li>
