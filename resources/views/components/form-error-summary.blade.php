@props(['errors', 'idMap' => []])

@if ($errors->any())
    <div
        role="alert"
        tabindex="-1"
        x-init="$el.focus()"
        {{ $attributes->merge(['class' => 'rounded-lg border border-destructive/25 bg-destructive/5 p-4 shadow-sm focus:outline-none focus:ring-2 focus:ring-destructive/40 focus:ring-offset-2']) }}
    >
        <p class="text-sm font-semibold text-destructive">{{ __('There is a problem') }}</p>
        <ul class="mt-2 space-y-1 text-sm leading-6 text-destructive/90">
            @foreach ($errors->messages() as $field => $messages)
                @foreach ($messages as $message)
                    <li>
                        <a
                            href="#{{ $idMap[$field] ?? $field }}"
                            class="rounded font-medium underline decoration-destructive/40 underline-offset-2 transition-colors hover:text-destructive focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-destructive"
                        >{{ $message }}</a>
                    </li>
                @endforeach
            @endforeach
        </ul>
    </div>
@endif
