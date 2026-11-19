@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'flex items-start gap-3 rounded-xl border border-success/20 bg-success/10 px-4 py-3 text-sm font-medium text-[#065F46] backdrop-blur-sm']) }} role="status">
        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-success text-white" aria-hidden="true">
            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <path d="m5 13 4 4L19 7" />
            </svg>
        </span>
        <span>{{ $status }}</span>
    </div>
@endif
