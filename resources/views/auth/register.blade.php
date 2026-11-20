<x-guest-layout :title="__('Create account')">
    <div class="border-b border-line pb-7">
        <p class="eyebrow">{{ __('Get started') }}</p>
        <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-foreground sm:text-4xl">{{ __('A clean slate.') }}</h1>
        <p class="mt-3 text-sm leading-6 text-muted-foreground">
            {{ __('Create an account to explore the workspace. Nothing here is tied to a real product.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="mt-7 space-y-5">
        @csrf

        <x-form-error-summary :errors="$errors" />

        <div>
            <x-input-label for="name" :value="__('Full name')" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" placeholder="{{ __('Ada Lovelace') }}" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" name="name" />
        </div>

        <div>
            <x-input-label for="tenant_name" :value="__('Workspace name')" />
            <x-text-input id="tenant_name" type="text" name="tenant_name" :value="old('tenant_name')" placeholder="{{ __('Globex Labs') }}" required />
            <x-input-error :messages="$errors->get('tenant_name')" name="tenant_name" />
        </div>

        <div>
            <x-input-label for="subdomain" :value="__('Subdomain')" />
            <div class="flex items-center">
                <x-text-input id="subdomain" type="text" name="subdomain" :value="old('subdomain')" placeholder="{{ __('globex') }}" class="rounded-r-none" autocomplete="off" />
                <span class="rounded-r-lg border border-l-0 border-line bg-canvas px-3 py-2 text-sm text-muted-foreground">.{{ config('tenancy.central_domains')[0] }}</span>
            </div>
            <p class="mt-1.5 text-xs text-muted-foreground">{{ __('Optional if you enter a custom domain.') }}</p>
            <x-input-error :messages="$errors->get('subdomain')" name="subdomain" />
        </div>

        <div>
            <x-input-label for="domain" :value="__('Custom domain')" />
            <x-text-input id="domain" type="text" name="domain" :value="old('domain')" placeholder="globex.com" class="w-full" autocomplete="off" />
            <p class="mt-1.5 text-xs text-muted-foreground">{{ __('Optional if you choose a subdomain. Include both to use both addresses.') }}</p>
            <x-input-error :messages="$errors->get('domain')" name="domain" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email address')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" placeholder="you@example.com" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" name="email" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" placeholder="{{ __('At least 8 characters') }}" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" name="password" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm password')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" placeholder="{{ __('Repeat your password') }}" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" name="password_confirmation" />
        </div>

        <button type="submit" class="btn-primary w-full">
            {{ __('Create account') }}
            <span aria-hidden="true">&rarr;</span>
        </button>
    </form>

    <p class="mt-8 border-t border-line pt-6 text-sm text-muted-foreground">
        {{ __('Already have an account?') }}
        <a href="{{ route('login') }}" class="rounded font-bold text-primary transition-colors hover:text-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
            {{ __('Sign in') }}
        </a>
    </p>

    <p class="mt-5 text-xs leading-5 text-muted-foreground">
        {{ __('By continuing you agree to the placeholder terms of this starter kit.') }}
    </p>
</x-guest-layout>
