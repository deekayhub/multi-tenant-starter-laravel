@props(['messages', 'name' => null])

@if ($messages)
    <ul
        @if ($name) id="{{ $name }}-error" @endif
        role="alert"
        {{ $attributes->merge(['class' => 'mt-2 space-y-1 text-xs font-medium text-destructive']) }}
    >
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
