<x-guest-layout :title="__('Confirm password')">
    <div class="border-b border-line pb-7">
        <p class="eyebrow">{{ __('Secure area') }}</p>
        <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-foreground sm:text-4xl">{{ __('Confirm it’s you.') }}</h1>
        <p class="mt-3 text-sm leading-6 text-muted-foreground">{{ __('Please confirm your password before continuing.') }}</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="mt-7 space-y-6">
        @csrf

        <x-form-error-summary :errors="$errors" />

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" name="password" />
        </div>

        <button type="submit" class="btn-primary w-full">{{ __('Confirm password') }}</button>
    </form>
</x-guest-layout>
