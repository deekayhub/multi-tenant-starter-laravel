<x-settings-layout
    :title="__('New role')"
    :description="__('Choose a name and the permissions this role should include.')"
>
    <div class="space-y-5">
        <div>
            <a href="{{ route('settings.roles.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-muted-foreground transition-colors hover:text-foreground">
                <x-icon name="chevron-right" class="h-3.5 w-3.5 rotate-180" />
                {{ __('Back to roles') }}
            </a>
        </div>

        <section class="surface-card">
            @include('tenant.roles.partials.form', [
                'action' => route('settings.roles.store'),
                'method' => 'POST',
                'role' => null,
                'submitLabel' => __('Create role'),
            ])
        </section>
    </div>
</x-settings-layout>
