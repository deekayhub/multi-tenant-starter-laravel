<x-guest-layout :title="__('Confirm password')">
    <div class="border-b border-line pb-7">
        <p class="eyebrow">{{ __('Secure area') }}</p>
        <h1 class="mt-2.5 text-2xl font-semibold tracking-tight text-foreground sm:text-3xl">{{ __('Confirm it’s you.') }}</h1>
        <p class="mt-2 text-sm leading-6 text-muted-foreground">{{ __('Please confirm your password before continuing.') }}</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="form-grid mt-8 gap-y-5">
        @csrf

        <x-form-error-summary :errors="$errors" />

        <div class="form-field">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" name="password" />
        </div>

        <div class="form-field-full border-t border-line pt-6">
            <button type="submit" class="btn-primary w-full">{{ __('Confirm password') }}</button>
        </div>
    </form>
</x-guest-layout>
