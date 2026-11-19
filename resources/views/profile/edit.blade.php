<x-app-layout :title="__('Profile')">
    <x-slot name="header">
        <p class="eyebrow">{{ __('Account') }}</p>
        <h2 class="mt-3 text-4xl font-extrabold leading-tight tracking-tight text-foreground sm:text-5xl">{{ __('Profile settings.') }}</h2>
        <p class="mt-3 text-sm text-muted-foreground">{{ __('Manage your account details and security preferences.') }}</p>
    </x-slot>

    <div class="mx-auto max-w-3xl space-y-5">
        <div class="surface-card p-5 sm:p-7">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="surface-card p-5 sm:p-7">
            @include('profile.partials.update-password-form')
        </div>

        <div class="surface-card border-destructive/30 p-5 sm:p-7">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
