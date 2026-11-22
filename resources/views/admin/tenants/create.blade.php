<x-admin-layout :title="__('New Tenant')">
    <x-slot name="header">
        <div>
            <p class="admin-eyebrow text-xs font-bold uppercase tracking-[0.18em] text-cyan-700">{{ __('Manual provisioning') }}</p>
            <h1 class="admin-title mt-2 text-2xl font-extrabold tracking-tight text-slate-950 sm:text-3xl">{{ __('Create Workspace') }}</h1>
            <p class="admin-subtitle mt-1.5 max-w-2xl text-sm text-slate-500">{{ __('Provision a tenant, its subdomain, and its primary Admin account in one step.') }}</p>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('admin.tenants.store') }}" class="admin-form max-w-5xl rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        @csrf

        <div class="mb-6">
            <h2 class="admin-panel-title text-sm font-bold text-slate-950">{{ __('Workspace details') }}</h2>
            <p class="admin-panel-subtitle mt-1 text-xs text-slate-500">{{ __('The workspace name is shown throughout the tenant application.') }}</p>
        </div>

        <div class="admin-form-grid form-grid">
            <div class="admin-form-field admin-form-field--full form-field-full">
                <x-input-label for="workspace_name" :value="__('Workspace name')" />
                <x-text-input id="workspace_name" name="workspace_name" type="text" :value="old('workspace_name')" required autofocus autocomplete="organization" placeholder="Acme Labs" class="admin-input" />
                <x-input-error :messages="$errors->get('workspace_name')" class="mt-2" />
            </div>

            <div class="admin-form-field admin-form-field--full form-field-full">
                <x-input-label for="subdomain" :value="__('Subdomain')" />
                <div class="flex items-stretch">
                    <x-text-input id="subdomain" name="subdomain" type="text" :value="old('subdomain')" required autocomplete="off" placeholder="acme" class="admin-input rounded-r-none" />
                    <span class="admin-input-suffix inline-flex items-center rounded-r-lg border border-l-0 border-line bg-muted px-3 text-sm text-muted-foreground">.{{ config('tenancy.central_domains')[0] }}</span>
                </div>
                <p class="admin-field-hint mt-1.5 text-xs text-slate-500">{{ __('Lowercase letters, numbers, and hyphens only.') }}</p>
                <x-input-error :messages="$errors->get('subdomain')" class="mt-2" />
            </div>
        </div>

        <div class="admin-form-divider my-7 border-t border-slate-200"></div>

        <div class="mb-6">
            <h2 class="admin-panel-title text-sm font-bold text-slate-950">{{ __('Owner account') }}</h2>
            <p class="admin-panel-subtitle mt-1 text-xs text-slate-500">{{ __('This user receives the protected Admin role inside the workspace.') }}</p>
        </div>

        <div class="admin-form-grid form-grid">
            <div class="admin-form-field form-field">
                <x-input-label for="owner_name" :value="__('Owner name')" />
                <x-text-input id="owner_name" name="owner_name" type="text" :value="old('owner_name')" required autocomplete="name" class="admin-input" />
                <x-input-error :messages="$errors->get('owner_name')" class="mt-2" />
            </div>

            <div class="admin-form-field form-field">
                <x-input-label for="owner_email" :value="__('Owner email')" />
                <x-text-input id="owner_email" name="owner_email" type="email" :value="old('owner_email')" required autocomplete="email" class="admin-input" />
                <x-input-error :messages="$errors->get('owner_email')" class="mt-2" />
            </div>

            <div class="admin-form-field form-field">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" name="password" type="password" required autocomplete="new-password" class="admin-input" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="admin-form-field form-field">
                <x-input-label for="password_confirmation" :value="__('Confirm password')" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="admin-input" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="admin-actions mt-8 flex flex-wrap items-center gap-3 border-t border-slate-200 pt-6">
            <button type="submit" class="admin-button btn-primary">
                <x-icon name="plus" class="h-4 w-4" />
                {{ __('Provision Workspace') }}
            </button>
            <a href="{{ route('admin.tenants.index') }}" class="admin-button admin-button--quiet btn-quiet">{{ __('Cancel') }}</a>
        </div>
    </form>
</x-admin-layout>
