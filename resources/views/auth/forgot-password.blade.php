<x-guest-layout :title="__('Reset password')">
    <div class="border-b border-line pb-7">
        <p class="eyebrow">{{ __('Account recovery') }}</p>
        <h1 class="mt-2.5 text-2xl font-semibold tracking-tight text-foreground sm:text-3xl">{{ __('Reset password.') }}</h1>
        <p class="mt-2 text-sm leading-6 text-muted-foreground">
            {{ __('Enter your email and we will send you a link to choose a new password.') }}
        </p>
    </div>

    <x-auth-session-status class="mt-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="form-grid mt-8 gap-y-5">
        @csrf

        <x-form-error-summary :errors="$errors" />

        <div class="form-field">
            <x-input-label for="email" :value="__('Email address')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" placeholder="you@example.com" required autofocus />
            <x-input-error :messages="$errors->get('email')" name="email" />
        </div>

        <div class="form-field-full border-t border-line pt-6">
            <button type="submit" class="btn-primary w-full">
                {{ __('Email reset link') }}
                <span aria-hidden="true">&rarr;</span>
            </button>
        </div>
    </form>

    <p class="mt-8 border-t border-line pt-6 text-sm text-muted-foreground">
        <a href="{{ route('login') }}" class="rounded font-medium text-muted-foreground underline decoration-line underline-offset-4 transition-colors hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
            &larr; {{ __('Back to sign in') }}
        </a>
    </p>
</x-guest-layout>
