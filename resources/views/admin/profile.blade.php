<x-admin-layout :title="__('Profile')">
    @php
        $verificationSendRoute = 'central.verification.send';
        $profileUpdateRoute = 'central.profile.update';
        $passwordUpdateRoute = 'central.password.update';
        $profileDestroyRoute = 'central.profile.destroy';
    @endphp

    <div class="space-y-6">
        <div class="glass-card p-6 sm:p-8">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="glass-card p-6 sm:p-8">
            @include('profile.partials.update-password-form')
        </div>

        <div class="glass-card mt-8 border-destructive/20 p-6 sm:p-8">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-admin-layout>
