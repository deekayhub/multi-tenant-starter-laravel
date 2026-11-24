@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'flex items-start gap-3 rounded-lg border border-success/25 bg-success/5 px-4 py-3 text-sm font-medium text-success']) }} role="status">
        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-success/15 text-success" aria-hidden="true">
            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <path d="m5 13 4 4L19 7" />
            </svg>
        </span>
        <span>{{ $status }}</span>
    </div>
@endif
