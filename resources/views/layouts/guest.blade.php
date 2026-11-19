@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" style="color-scheme: light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#F8FAFC">

        <title>{{ $title ? $title.' · ' : '' }}{{ config('app.name', 'Orbit') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="relative min-h-full overflow-x-hidden bg-canvas text-foreground">
        <!-- Ambient light source -->
        <div class="pointer-events-none fixed -left-40 -top-40 h-[32rem] w-[32rem] rounded-full bg-primary/10 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none fixed -bottom-48 left-1/3 h-[28rem] w-[28rem] rounded-full bg-secondary/10 blur-3xl" aria-hidden="true"></div>

        <div class="relative min-h-screen lg:grid lg:grid-cols-[minmax(0,1fr)_minmax(0,1.05fr)]">
            <!-- Form column -->
            <div class="flex min-h-screen flex-col">
                <header class="flex h-20 shrink-0 items-center justify-between gap-4 px-6 sm:px-10">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-3 rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2" aria-label="{{ __('Starter kit home') }}">
                        <x-brand-mark class="h-9 w-9" />
                        <span class="text-lg font-extrabold tracking-tight">orbit</span>
                        <span class="hidden border-l border-line pl-3 text-[10px] font-bold uppercase tracking-[0.16em] text-muted-foreground sm:inline">Starter kit</span>
                    </a>

                    <a href="{{ url('/') }}" class="group inline-flex items-center gap-2 rounded-lg text-xs font-bold uppercase tracking-[0.12em] text-muted-foreground transition-colors hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                        <span class="transition-transform duration-150 group-hover:-translate-x-0.5" aria-hidden="true">&larr;</span>
                        {{ __('Home') }}
                    </a>
                </header>

                <main class="flex flex-1 items-center justify-center px-4 py-8 sm:px-10">
                    <div class="w-full max-w-[30rem]">
                        <div class="glass-card p-6 sm:p-9">
                            {{ $slot }}
                        </div>
                    </div>
                </main>

                <footer class="shrink-0 px-6 pb-8 sm:px-10">
                    <div class="flex flex-col gap-3 border-t border-line pt-6 text-xs text-muted-foreground sm:flex-row sm:items-center sm:justify-between">
                        <p>&copy; {{ date('Y') }} Orbit. {{ __('A starter for new ideas.') }}</p>
                        <div class="flex items-center gap-5">
                            <a href="#" class="rounded transition-colors hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">{{ __('Privacy') }}</a>
                            <a href="#" class="rounded transition-colors hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">{{ __('Terms') }}</a>
                            <a href="#" class="rounded transition-colors hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">{{ __('Help') }}</a>
                        </div>
                    </div>
                </footer>
            </div>

            <!-- Brand panel -->
            <aside class="relative hidden overflow-hidden bg-brand-gradient text-white lg:sticky lg:top-0 lg:flex lg:min-h-screen lg:flex-col lg:justify-between lg:px-12 lg:py-10 xl:px-16">
                <div class="pointer-events-none absolute inset-0 bg-brand-sheen" aria-hidden="true"></div>
                <div class="pointer-events-none absolute -right-24 top-24 h-96 w-96 rounded-full border border-white/20 bg-white/10 backdrop-blur-sm" aria-hidden="true"></div>
                <div class="pointer-events-none absolute -bottom-40 -left-20 h-[30rem] w-[30rem] rounded-full bg-white/10 blur-2xl" aria-hidden="true"></div>
                <div class="pointer-events-none absolute inset-0 opacity-[0.12]" style="background-image: linear-gradient(to right, #fff 1px, transparent 1px), linear-gradient(to bottom, #fff 1px, transparent 1px); background-size: 72px 72px;" aria-hidden="true"></div>

                <div class="relative flex items-center justify-between gap-4 text-[11px] font-bold uppercase tracking-[0.18em] text-white/80">
                    <span>{{ __('Multi-tenant foundation') }}</span>
                    <span class="rounded-lg border border-white/30 bg-white/10 px-2.5 py-1 backdrop-blur-sm">v1.0</span>
                </div>

                <div class="relative max-w-xl py-12">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-white/80">{{ __('Built to be reshaped') }}</p>
                    <h2 class="mt-5 text-5xl font-extrabold leading-[1.05] tracking-tight xl:text-6xl">{{ __('One app.') }}<br>{{ __('Every customer.') }}</h2>
                    <p class="mt-6 max-w-md text-sm leading-7 text-white/85">
                        {{ __('A clean starting point for SaaS products: authentication, workspaces, roles and a dashboard you can replace in an afternoon.') }}
                    </p>

                    <div class="glass-panel mt-10 max-w-md overflow-hidden">
                        <div class="flex items-center justify-between border-b border-white/20 px-4 py-3">
                            <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-white/80">{{ __('Workspace directory') }}</p>
                            <p class="text-xs text-white/80">03</p>
                        </div>
                        <ul class="divide-y divide-white/15">
                            <li class="flex items-center gap-3 px-4 py-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/30 bg-white/15 text-[11px] font-bold">AC</span>
                                <span class="flex-1 text-sm font-medium">Acme Workspace</span>
                                <span class="text-[11px] font-semibold text-white/80">{{ __('Active') }}</span>
                            </li>
                            <li class="flex items-center gap-3 px-4 py-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/30 bg-white/15 text-[11px] font-bold">GL</span>
                                <span class="flex-1 text-sm font-medium">Globex Labs</span>
                                <span class="text-[11px] font-semibold text-white/80">{{ __('Trial') }}</span>
                            </li>
                            <li class="flex items-center gap-3 px-4 py-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/30 bg-white/15 text-[11px] font-bold">IN</span>
                                <span class="flex-1 text-sm font-medium">Initech Co.</span>
                                <span class="text-[11px] font-semibold text-white/80">{{ __('Active') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="relative flex flex-wrap items-center justify-between gap-3 border-t border-white/20 pt-5 text-[11px] font-bold uppercase tracking-[0.14em] text-white/80">
                    <span>Auth &middot; Tenancy &middot; Roles &middot; Activity</span>
                    <span>{{ __('Placeholder content') }}</span>
                </div>
            </aside>
        </div>
    </body>
</html>
