<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="color-scheme: light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#F8FAFC">
        <title>{{ __('Workspace Suspended') }} · {{ config('app.name', 'Orbit') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-canvas text-foreground">
        <main class="flex min-h-screen items-center justify-center px-4 py-12">
            <section class="w-full max-w-lg rounded-2xl border border-line bg-card p-8 text-center shadow-glass" aria-labelledby="suspended-title">
                <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-warning/20 text-warning" aria-hidden="true">
                    <x-icon name="alert" class="h-6 w-6" />
                </span>

                <p class="mt-6 text-xs font-bold uppercase tracking-[0.18em] text-muted-foreground">{{ $tenant?->name ?? config('app.name', 'Orbit') }}</p>
                <h1 id="suspended-title" class="mt-2 text-2xl font-extrabold tracking-tight text-foreground sm:text-3xl">{{ __('Workspace Suspended') }}</h1>
                <p class="mt-3 text-sm leading-6 text-muted-foreground">
                    {{ __('This workspace is currently unavailable. If you believe this is a mistake, please contact your SaaS administrator.') }}
                </p>

                <div class="mt-7 border-t border-line pt-5">
                    <a href="{{ config('app.url') }}" class="text-sm font-semibold text-primary hover:text-primary-dark">
                        {{ __('Return to central site') }}
                    </a>
                </div>
            </section>
        </main>
    </body>
</html>
