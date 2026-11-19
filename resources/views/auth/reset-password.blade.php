<x-guest-layout :title="__('Choose password')">
    <div class="border-b border-line pb-7">
        <p class="eyebrow">{{ __('Account recovery') }}</p>
        <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-foreground sm:text-4xl">{{ __('Choose a password.') }}</h1>
        <p class="mt-3 text-sm leading-6 text-muted-foreground">{{ __('Pick something new for your account.') }}</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="mt-7 space-y-5">
        @csrf

        <x-form-error-summary :errors="$errors" />

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" :value="__('Email address')" />
            <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" name="email" />
        </div>

        <div>
            <x-input-label for="password" :value="__('New password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" name="password" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm password')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" name="password_confirmation" />
        </div>

        <button type="submit" class="btn-primary w-full">
            {{ __('Update password') }}
            <span aria-hidden="true">&rarr;</span>
        </button>
    </form>
</x-guest-layout>
