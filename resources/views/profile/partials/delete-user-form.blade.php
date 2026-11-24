<section class="space-y-6">
    <header class="border-b border-line pb-5">
        <h2 class="text-lg font-semibold leading-tight tracking-tight text-destructive">{{ __('Delete account') }}</h2>
        <p class="mt-1.5 text-sm leading-6 text-muted-foreground">
            {{ __('Once your account is deleted, its data is permanently removed. Download anything you wish to keep first.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" :label="__('Confirm account deletion')" focusable>
        <form method="post" action="{{ route($profileDestroyRoute ?? 'profile.destroy') }}" class="form-grid gap-y-5 p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold tracking-tight text-foreground">{{ __('Are you sure?') }}</h2>

            <p class="mt-2 text-sm leading-6 text-muted-foreground">
                {{ __('This cannot be undone. Enter your password to confirm.') }}
            </p>

            <x-form-error-summary :errors="$errors->userDeletion" />

            <div class="form-field-full mt-5">
                <x-input-label for="password" :value="__('Password')" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="{{ __('Password') }}"
                    :error-bag="$errors->userDeletion"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" name="password" />
            </div>

            <div class="form-field-full mt-5 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button>
                    {{ __('Delete account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
