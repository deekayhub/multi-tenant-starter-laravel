<section>
    <header class="border-b border-line pb-5">
        <h2 class="text-2xl font-extrabold leading-tight text-foreground">{{ __('Profile information') }}</h2>
        <p class="mt-2 text-sm leading-6 text-muted-foreground">{{ __("Update your account's name and email address.") }}</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="form-grid mt-5">
        @csrf
        @method('patch')

        <x-form-error-summary :errors="$errors" />

        <div class="form-field">
            <x-input-label for="name" :value="__('Full name')" />
            <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" name="name" />
        </div>

        <div class="form-field">
            <x-input-label for="email" :value="__('Email address')" />
            <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" name="email" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 rounded-xl border border-warning/25 bg-warning/10 px-4 py-3 text-sm text-muted-foreground">
                    <p>{{ __('Your email address is unverified.') }}</p>
                    <button form="send-verification" class="mt-1 rounded font-semibold text-primary transition-colors hover:text-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-semibold text-success">{{ __('A new verification link has been sent to your email address.') }}</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="form-field-full flex items-center gap-4 border-t border-line pt-4">
            <x-primary-button>{{ __('Save changes') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-[11px] font-semibold text-success"
                    role="status"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
