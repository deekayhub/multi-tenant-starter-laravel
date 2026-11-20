<x-guest-layout :title="__('Create account')">
    <div class="border-b border-line pb-6">
        <p class="eyebrow">{{ __('Get started') }}</p>
        <h1 class="mt-2.5 text-3xl font-extrabold tracking-tight text-foreground sm:text-4xl">{{ __('A clean slate.') }}</h1>
        <p class="mt-2 text-sm leading-6 text-muted-foreground">
            {{ __('Create a workspace on its own subdomain. Everything shown here is placeholder content.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="form-grid mt-6">
        @csrf

        <x-form-error-summary :errors="$errors" class="form-field-full" />

        <div class="form-field">
            <x-input-label for="name" :value="__('Full name')" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" placeholder="{{ __('Ada Lovelace') }}" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" name="name" />
        </div>

        <div class="form-field">
            <x-input-label for="tenant_name" :value="__('Workspace name')" />
            <x-text-input id="tenant_name" type="text" name="tenant_name" :value="old('tenant_name')" placeholder="{{ __('Globex Labs') }}" required />
            <x-input-error :messages="$errors->get('tenant_name')" name="tenant_name" />
        </div>

        <div class="form-field">
            <x-input-label for="subdomain" :value="__('Subdomain')" />
            <div class="flex items-center">
                <x-text-input id="subdomain" type="text" name="subdomain" :value="old('subdomain')" placeholder="{{ __('globex') }}" class="rounded-r-none" autocomplete="off" />
                <span class="flex h-11 items-center rounded-r-lg border border-l-0 border-line bg-canvas px-3 text-sm text-muted-foreground">.{{ config('tenancy.central_domains')[0] }}</span>
            </div>
            <p class="mt-1.5 text-xs text-muted-foreground">{{ __('This will be your workspace address. You can add a custom domain later in settings.') }}</p>
            <x-input-error :messages="$errors->get('subdomain')" name="subdomain" />
        </div>

        <div class="form-field">
            <x-input-label for="email" :value="__('Email address')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" placeholder="you@example.com" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" name="email" />
        </div>

        <div class="form-field">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" placeholder="{{ __('At least 8 characters') }}" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" name="password" />
        </div>

        <div class="form-field">
            <x-input-label for="password_confirmation" :value="__('Confirm password')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" placeholder="{{ __('Repeat your password') }}" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" name="password_confirmation" />
        </div>

        <div class="form-field-full flex justify-end border-t border-line pt-4">
            <button type="submit" class="btn-primary">
                {{ __('Create account') }}
                <span aria-hidden="true">&rarr;</span>
            </button>
        </div>
    </form>

    <p class="mt-6 border-t border-line pt-5 text-sm text-muted-foreground">
        {{ __('Already have an account?') }}
        <a href="{{ route('login') }}" class="rounded font-bold text-primary transition-colors hover:text-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
            {{ __('Sign in') }}
        </a>
    </p>

    <p class="mt-4 text-xs leading-5 text-muted-foreground">
        {{ __('By continuing you agree to the placeholder terms of this starter kit.') }}
    </p>
</x-guest-layout>
