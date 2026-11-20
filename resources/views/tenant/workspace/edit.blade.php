<x-settings-layout :title="__('Workspace')">
    <div class="space-y-5">
        @if (session('status'))
            <div role="status" class="rounded-xl border border-success/25 bg-success/10 px-4 py-3 text-sm font-semibold text-success">
                {{ session('status') }}
            </div>
        @endif

        <section class="surface-card">
            <x-form-error-summary :errors="$errors" :id-map="['name' => 'name', 'email' => 'email', 'custom_domain' => 'custom_domain']" />

            <form method="POST" action="{{ route('settings.workspace.update') }}" class="form-grid">
                @csrf
                @method('PATCH')

                <div class="form-field">
                    <label for="name" class="field-label">{{ __('Workspace name') }}</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $tenant->name) }}"
                        autocomplete="organization"
                        class="field-input @error('name') field-input-error @enderror"
                    >
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                <div class="form-field">
                    <label for="email" class="field-label">{{ __('Workspace email') }}</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', $tenant->email) }}"
                        autocomplete="email"
                        class="field-input @error('email') field-input-error @enderror"
                    >
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <div class="form-field">
                    <p class="field-label">{{ __('Workspace ID') }}</p>
                    <p class="flex h-11 items-center rounded-lg border border-line bg-muted/40 px-3.5 font-mono text-xs text-muted-foreground">{{ $tenant->getKey() }}</p>
                </div>

                <div class="form-field">
                    <p class="field-label">{{ __('Subdomain') }}</p>
                    <div class="flex items-center">
                        <input
                            type="text"
                            value="{{ $subdomain }}"
                            disabled
                            class="field-input rounded-r-none bg-muted/40 text-muted-foreground"
                            aria-label="{{ __('Subdomain') }}"
                        >
                        <span class="flex h-11 items-center rounded-r-lg border border-l-0 border-line bg-canvas px-3 text-sm text-muted-foreground">.{{ config('tenancy.central_domains')[0] }}</span>
                    </div>
                    <p class="mt-1.5 text-xs text-muted-foreground">{{ __('Your subdomain cannot be changed from this page.') }}</p>
                </div>

                <div class="form-field-full">
                    <label for="custom_domain" class="field-label">{{ __('Custom domain') }}</label>
                    <input
                        id="custom_domain"
                        name="custom_domain"
                        type="text"
                        value="{{ old('custom_domain', $customDomain) }}"
                        placeholder="workspace.example.com"
                        autocomplete="off"
                        class="field-input @error('custom_domain') field-input-error @enderror"
                    >
                    <p class="mt-1.5 text-xs text-muted-foreground">{{ __('Point this domain to the app to use it. Leave blank to remove the custom domain.') }}</p>
                    <x-input-error :messages="$errors->get('custom_domain')" />
                </div>

                <div class="form-field-full flex flex-wrap items-center gap-3 border-t border-line pt-4">
                    <button type="submit" class="btn-primary">{{ __('Save changes') }}</button>
                </div>
            </form>
        </section>
    </div>
</x-settings-layout>
