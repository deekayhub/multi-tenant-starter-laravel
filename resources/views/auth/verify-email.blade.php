<x-guest-layout :title="__('Verify email')">
    @php
        $logoutUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'central.logout',
            now()->addDay(),
        );
    @endphp

    <div class="border-b border-line pb-7">
        <p class="eyebrow">{{ __('One last step') }}</p>
        <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-foreground sm:text-4xl">{{ __('Check your inbox.') }}</h1>
        <p class="mt-3 text-sm leading-6 text-muted-foreground">
            {{ __('Thanks for signing up. Follow the link we emailed you to verify your address before continuing.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <x-auth-session-status class="mt-7" :status="__('A new verification link has been sent to your email address.')" />
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="mt-6 flex justify-end">
        @csrf
        <button type="submit" class="btn-primary">{{ __('Resend verification email') }}</button>
    </form>

    <form method="POST" action="{{ $logoutUrl }}" class="mt-2 flex justify-end">
        @csrf
        <button type="submit" class="btn-quiet">{{ __('Log out') }}</button>
    </form>
</x-guest-layout>
