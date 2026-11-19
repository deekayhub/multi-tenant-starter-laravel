<x-guest-layout :title="__('Verify email')">
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

    <form method="POST" action="{{ route('verification.send') }}" class="mt-7">
        @csrf
        <button type="submit" class="btn-primary w-full">{{ __('Resend verification email') }}</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-3">
        @csrf
        <button type="submit" class="btn-quiet w-full">{{ __('Log out') }}</button>
    </form>
</x-guest-layout>
