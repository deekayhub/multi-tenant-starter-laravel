<x-settings-layout
    :title="__('Profile')"
    :description="__('Manage your account details and security preferences.')"
>
    <div class="space-y-5">
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
</x-settings-layout>
