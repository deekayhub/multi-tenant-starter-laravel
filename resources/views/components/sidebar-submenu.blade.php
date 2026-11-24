@props([
    'label',
    'icon',
    'children' => [],
    'active' => false,
    'id',
    'collapsible' => false,
])

<li
    x-data="{
        open: @json($active),
        toggle(fromKeyboard = false) {
            this.open = ! this.open;

            if (this.open && fromKeyboard) {
                this.$nextTick(() => this.$refs.panel?.querySelector('a')?.focus());
            }
        },
        close() {
            this.open = false;
            this.$refs.trigger?.focus();
        },
    }"
    @keydown.escape.stop.prevent="close()"
>
    <button
        id="{{ $id }}-trigger"
        x-ref="trigger"
        type="button"
        @click="toggle($event.detail === 0)"
        :aria-expanded="String(open)"
        aria-controls="{{ $id }}"
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
        <x-icon
            name="chevron-down"
            class="h-3.5 w-3.5 shrink-0 text-white/70 transition-transform duration-200"
            @if ($collapsible) x-show="! sidebarCollapsed" @endif
            x-bind:class="open ? 'rotate-180' : ''"
            x-cloak
        />
    </button>

    <div
        class="transition-all duration-200 ease-out"
        x-show="open"
        x-transition:enter="transition-all duration-200 ease-out"
        x-transition:enter-start="max-h-0 opacity-0 -translate-y-1"
        x-transition:enter-end="max-h-60 opacity-100 translate-y-0"
        x-transition:leave="transition-all duration-150 ease-in"
        x-transition:leave-start="max-h-60 opacity-100 translate-y-0"
        x-transition:leave-end="max-h-0 opacity-0 -translate-y-1"
        @if ($collapsible) x-bind:class="sidebarCollapsed ? 'hidden' : ''" @endif
        x-cloak
    >
        <ul id="{{ $id }}" x-ref="panel" aria-labelledby="{{ $id }}-trigger" class="flex flex-col gap-0.5 pl-3">
            @foreach ($children as $child)
                <li>
                    <a
                        href="{{ $child['href'] }}"
                        @if ($child['active']) aria-current="page" @endif
                        @class([
                            'flex items-center gap-2 rounded-md px-2 py-1.5 text-[13px] font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/60',
                            'app-sidebar-subactive' => $child['active'],
                            'text-zinc-500 hover:bg-white/5 hover:text-zinc-100' => ! $child['active'],
                        ])
                    >
                        <x-icon :name="$child['icon']" class="h-3.5 w-3.5 shrink-0" />
                        <span class="truncate">{{ $child['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</li>
