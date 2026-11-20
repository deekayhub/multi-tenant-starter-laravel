<x-settings-layout
    :title="__('Edit role')"
    :description="__('Update the name or permissions for :role.', ['role' => $role->name])"
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
                'action' => route('settings.roles.update', $role),
                'method' => 'PUT',
                'submitLabel' => __('Save changes'),
            ])
        </section>
    </div>
</x-settings-layout>
