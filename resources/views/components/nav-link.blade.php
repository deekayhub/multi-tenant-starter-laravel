@props(['active'])

@php
$classes = $active ?? false
    ? 'inline-flex items-center rounded-lg bg-primary/10 px-3 py-2 text-sm font-semibold text-primary'
    : 'inline-flex items-center rounded-lg px-3 py-2 text-sm font-semibold text-muted-foreground transition-colors hover:bg-muted hover:text-foreground';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
