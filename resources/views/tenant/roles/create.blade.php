<x-app-layout :title="__('New role')">
    <div class="mx-auto max-w-3xl space-y-7">
        <div>
            <a href="{{ route('tenant.roles.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-muted-foreground transition-colors hover:text-foreground">
                <x-icon name="chevron-right" class="h-3.5 w-3.5 rotate-180" />
                {{ __('Back to roles') }}
            </a>
            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-foreground">{{ __('New role') }}</h1>
            <p class="mt-2 text-sm leading-6 text-muted-foreground">{{ __('Choose a name and the permissions this role should include.') }}</p>
        </div>

        <section class="surface-card">
            @include('tenant.roles.partials.form', [
                'action' => route('tenant.roles.store'),
                'method' => 'POST',
                'role' => null,
                'submitLabel' => __('Create role'),
            ])
        </section>
    </div>
</x-app-layout>
