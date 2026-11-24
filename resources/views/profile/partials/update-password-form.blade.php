<section>
    <header class="border-b border-line pb-5">
        <h2 class="text-lg font-semibold leading-tight tracking-tight text-foreground">{{ __('Update password') }}</h2>
        <p class="mt-1.5 text-sm leading-6 text-muted-foreground">{{ __('Use a long, unique password to keep your account secure.') }}</p>
    </header>

    <form method="post" action="{{ route($passwordUpdateRoute ?? 'password.update') }}" class="form-grid mt-6 gap-x-5 gap-y-5">
        @csrf
        @method('put')

        <x-form-error-summary
            :errors="$errors->updatePassword"
            :id-map="['current_password' => 'update_password_current_password', 'password' => 'update_password_password', 'password_confirmation' => 'update_password_password_confirmation']"
        />

        <div class="form-field">
            <x-input-label for="update_password_current_password" :value="__('Current password')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" :error-bag="$errors->updatePassword" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" name="current_password" />
        </div>

        <div class="form-field">
            <x-input-label for="update_password_password" :value="__('New password')" />
            <x-text-input id="update_password_password" name="password" type="password" autocomplete="new-password" :error-bag="$errors->updatePassword" />
            <x-input-error :messages="$errors->updatePassword->get('password')" name="password" />
        </div>

        <div class="form-field">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" :error-bag="$errors->updatePassword" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" name="password_confirmation" />
        </div>

        <div class="form-field-full flex flex-wrap items-center gap-4 border-t border-line pt-6">
            <x-primary-button>{{ __('Update password') }}</x-primary-button>

            @if (session('status') === 'password-updated')
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
