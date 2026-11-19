@props(['errors', 'idMap' => []])

@if ($errors->any())
    <div
        role="alert"
        tabindex="-1"
        x-init="$el.focus()"
        class="rounded-xl border border-destructive/25 bg-destructive/10 p-4 shadow-sm backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-destructive focus:ring-offset-2"
    >
        <p class="text-sm font-bold text-[#991B1B]">{{ __('There is a problem') }}</p>
        <ul class="mt-2 space-y-1.5 text-sm leading-6 text-[#7F1D1D]">
            @foreach ($errors->messages() as $field => $messages)
                @foreach ($messages as $message)
                    <li>
                        <a
                            href="#{{ $idMap[$field] ?? $field }}"
                            class="rounded underline decoration-destructive/40 underline-offset-2 transition-colors hover:text-destructive focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                        >{{ $message }}</a>
                    </li>
                @endforeach
            @endforeach
        </ul>
    </div>
@endif
