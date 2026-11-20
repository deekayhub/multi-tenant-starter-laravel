<x-settings-layout :title="__('Add member')">
    <div class="space-y-5">
        <div>
            <a href="{{ route('settings.members.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-muted-foreground transition-colors hover:text-foreground">
                <x-icon name="chevron-right" class="h-3.5 w-3.5 rotate-180" />
                {{ __('Back to team members') }}
            </a>
        </div>

        <section class="surface-card">
            @include('tenant.members.partials.form', [
                'action' => route('settings.members.store'),
                'method' => 'POST',
                'member' => null,
                'isSoleAdmin' => false,
                'submitLabel' => __('Add member'),
            ])
        </section>
    </div>
</x-settings-layout>
