@props(['active'])

@php
$classes = $active ?? false
    ? 'block rounded-lg bg-primary/10 px-3 py-2 text-base font-semibold text-primary'
    : 'block rounded-lg px-3 py-2 text-base font-semibold text-muted-foreground transition-colors hover:bg-muted hover:text-foreground';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
