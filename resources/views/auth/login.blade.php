<x-guest-layout :title="__('Sign in')">
    <div class="mb-8">
        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-800 ring-1 ring-emerald-100">
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500" aria-hidden="true"></span>
            Welcome back
        </span>
        <h1 class="mt-4 text-3xl font-semibold tracking-tight text-slate-900">{{ __('Sign in to Stoq') }}</h1>
        <p class="mt-2 text-sm leading-6 text-slate-600">Pick up where you left off — your stock levels are already up to date.</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 flex items-start gap-2.5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800" role="status">
            <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="mb-1.5 block text-sm font-medium text-slate-900">{{ __('Email address') }}</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-11 items-center justify-center text-slate-400" aria-hidden="true">
                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
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
                    placeholder="you@yourshop.com"
                    @class([
                        'block w-full rounded-xl border bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm transition-colors placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-offset-0',
                        'border-slate-300 focus:border-emerald-600 focus:ring-emerald-600/25' => ! $errors->has('email'),
                        'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' => $errors->has('email'),
                    ])
                    @if ($errors->has('email')) aria-invalid="true" aria-describedby="email-error" @endif
                >
            </div>
            @error('email')
                <p id="email-error" class="mt-2 flex items-start gap-1.5 text-xs font-medium text-rose-600">
                    <svg class="mt-px h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 9v3.75m0 3.75h.008M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Password -->
        <div x-data="{ visible: false }">
            <div class="mb-1.5 flex items-baseline justify-between gap-4">
                <label for="password" class="block text-sm font-medium text-slate-900">{{ __('Password') }}</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="rounded text-xs font-semibold text-emerald-700 transition-colors hover:text-emerald-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-11 items-center justify-center text-slate-400" aria-hidden="true">
                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
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
                    placeholder="Enter your password"
                    @class([
                        'block w-full rounded-xl border bg-white py-3 pl-11 pr-12 text-sm text-slate-900 shadow-sm transition-colors placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-offset-0',
                        'border-slate-300 focus:border-emerald-600 focus:ring-emerald-600/25' => ! $errors->has('password'),
                        'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' => $errors->has('password'),
                    ])
                    @if ($errors->has('password')) aria-invalid="true" aria-describedby="password-error" @endif
                >
                <button
                    type="button"
                    @click="visible = !visible"
                    aria-label="Show password"
                    aria-pressed="false"
                    :aria-label="visible ? 'Hide password' : 'Show password'"
                    :aria-pressed="visible"
                    class="absolute inset-y-0 right-0 flex w-11 items-center justify-center rounded-r-xl text-slate-400 transition-colors hover:text-slate-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600"
                >
                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" x-show="! visible">
                        <path d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                        <path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" x-show="visible" x-cloak>
                        <path d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p id="password-error" class="mt-2 flex items-start gap-1.5 text-xs font-medium text-rose-600">
                    <svg class="mt-px h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 9v3.75m0 3.75h.008M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between gap-4 pt-1">
            <label for="remember_me" class="group inline-flex cursor-pointer items-center gap-2.5">
                <input
                    id="remember_me"
                    name="remember"
                    type="checkbox"
                    class="h-[18px] w-[18px] rounded-[5px] border-slate-300 text-emerald-700 shadow-sm transition-colors focus:ring-2 focus:ring-emerald-600/30 focus:ring-offset-0"
                >
                <span class="text-sm text-slate-600 group-hover:text-slate-900">{{ __('Keep me signed in') }}</span>
            </label>
        </div>

        <button
            type="submit"
            class="group inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-700 px-4 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-emerald-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2"
        >
            {{ __('Sign in') }}
            <svg class="h-4 w-4 transition-transform duration-150 group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
            </svg>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-600">
        {{ __('New to Stoq?') }}
        <a href="{{ route('register') }}" class="rounded font-semibold text-emerald-700 transition-colors hover:text-emerald-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2">
            {{ __('Create an account') }}
        </a>
    </p>

    <div class="mt-10 rounded-xl border border-slate-200 bg-slate-50/70 px-4 py-3.5">
        <div class="flex items-start gap-2.5">
            <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <p class="text-xs leading-5 text-slate-600">
                <span class="font-semibold text-slate-800">Your data stays yours.</span>
                Encrypted in transit, never sold, and exportable at any time.
            </p>
        </div>
    </div>
</x-guest-layout>
