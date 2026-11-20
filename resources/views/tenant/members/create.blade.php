<x-app-layout :title="__('Add member')">
    <div class="mx-auto max-w-3xl space-y-7">
        <div>
            <a href="{{ route('tenant.members.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-muted-foreground transition-colors hover:text-foreground">
                <x-icon name="chevron-right" class="h-3.5 w-3.5 rotate-180" />
                {{ __('Back to team members') }}
            </a>
            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-foreground">{{ __('Add member') }}</h1>
            <p class="mt-2 text-sm leading-6 text-muted-foreground">{{ __('Create an account and choose the roles this member should hold.') }}</p>
        </div>

        <section class="surface-card">
            @include('tenant.members.partials.form', [
                'action' => route('tenant.members.store'),
                'method' => 'POST',
                'member' => null,
                'submitLabel' => __('Add member'),
            ])
        </section>
    </div>
</x-app-layout>
