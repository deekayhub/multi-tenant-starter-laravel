<x-guest-layout :title="__('Sign in')">
    <div class="border-b border-line pb-7">
        <p class="eyebrow">{{ __('Account access') }}</p>
        <h1 class="mt-2.5 text-2xl font-semibold tracking-tight text-foreground sm:text-3xl">{{ __('Welcome back.') }}</h1>
        <p class="mt-2 text-sm leading-6 text-muted-foreground">
            {{ __('Sign in to your workspace. All content in this starter is placeholder data.') }}
        </p>
    </div>

    <x-auth-session-status class="mt-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="form-grid mt-8 gap-y-5">
        @csrf

        <x-form-error-summary :errors="$errors" />

        <div class="form-field">
            <label for="email" class="field-label">{{ __('Email address') }}</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-11 items-center justify-center text-muted-foreground" aria-hidden="true">
                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                </span>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="you@example.com"
                    @class([
                        'field-input pl-11 pr-3.5',
                        'field-input-error' => $errors->has('email'),
                    ])
                    @if ($errors->has('email')) aria-invalid="true" aria-describedby="email-error" @endif
                >
            </div>
            @error('email')
                <p id="email-error" role="alert" class="mt-1.5 text-xs font-medium leading-5 text-destructive">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-field" x-data="{ visible: false }">
            <div class="mb-2 flex items-baseline justify-between gap-4">
                <label for="password" class="field-label mb-0">{{ __('Password') }}</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="rounded text-xs font-medium text-muted-foreground underline decoration-line underline-offset-4 transition-colors hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-11 items-center justify-center text-muted-foreground" aria-hidden="true">
                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </span>
                <input
                    id="password"
                    name="password"
                    type="password"
                    x-bind:type="visible ? 'text' : 'password'"
                    required
                    autocomplete="current-password"
                    placeholder="{{ __('Enter your password') }}"
                    @class([
                        'field-input pl-11 pr-11',
                        'field-input-error' => $errors->has('password'),
                    ])
                    @if ($errors->has('password')) aria-invalid="true" aria-describedby="password-error" @endif
                >
                <button
                    type="button"
                    @click="visible = !visible"
                    :aria-label="visible ? '{{ __('Hide password') }}' : '{{ __('Show password') }}'"
                    :aria-pressed="visible"
                    class="absolute inset-y-0 right-0 flex w-11 items-center justify-center rounded-r-lg text-muted-foreground transition-colors hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                >
                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" x-show="! visible">
                        <path d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                        <path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" x-show="visible" x-cloak>
                        <path d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p id="password-error" role="alert" class="mt-1.5 text-xs font-medium leading-5 text-destructive">{{ $message }}</p>
            @enderror
        </div>

        <label for="remember_me" class="form-field-full group inline-flex cursor-pointer items-center gap-3">
            <input
                id="remember_me"
                name="remember"
                type="checkbox"
                class="h-4 w-4 rounded border-line text-primary shadow-sm transition-colors focus:ring-2 focus:ring-primary/30 focus:ring-offset-0"
            >
            <span class="text-sm font-medium text-muted-foreground group-hover:text-foreground">{{ __('Keep me signed in') }}</span>
        </label>

        <div class="form-field-full border-t border-line pt-6">
            <button type="submit" class="btn-primary w-full">
                {{ __('Sign in') }}
                <span aria-hidden="true">&rarr;</span>
            </button>
        </div>
    </form>

    <p class="mt-8 border-t border-line pt-6 text-sm text-muted-foreground">
        {{ __('New to this starter?') }}
        <a href="{{ route('register') }}" class="rounded font-medium text-foreground underline decoration-line underline-offset-4 transition-colors hover:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
            {{ __('Create an account') }}
        </a>
    </p>

    <p class="mt-5 text-xs leading-5 text-muted-foreground">
        {{ __('Demo environment — workspace names and records shown here are placeholders.') }}
    </p>
</x-guest-layout>
