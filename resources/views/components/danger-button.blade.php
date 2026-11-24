<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg border border-destructive/30 bg-white px-4 py-2 text-sm font-medium text-destructive shadow-sm transition-colors duration-150 hover:border-destructive/50 hover:bg-destructive/5 active:translate-y-px focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-destructive focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50']) }}>
    {{ $slot }}
</button>
