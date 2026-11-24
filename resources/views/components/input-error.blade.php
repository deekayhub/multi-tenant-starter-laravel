@props(['messages', 'name' => null])

@if ($messages)
    <ul
        @if ($name) id="{{ $name }}-error" @endif
        role="alert"
        {{ $attributes->merge(['class' => 'mt-1.5 space-y-0.5 text-xs font-medium leading-5 text-destructive']) }}
    >
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
