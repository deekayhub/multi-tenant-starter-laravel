@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" style="color-scheme: light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#ffffff">

        <title>{{ $title ? $title.' · ' : '' }}{{ config('app.name', 'Stoq') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&amp;display=swap" rel="stylesheet" />
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak] { display: none !important; }

            .auth-shell {
                font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif;
                font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11';
            }

            @media (prefers-reduced-motion: reduce) {
                *, *::before, *::after {
                    animation-duration: 0.01ms !important;
                    animation-iteration-count: 1 !important;
                    transition-duration: 0.01ms !important;
                }
            }
        </style>
    </head>
    <body class="auth-shell min-h-full bg-white text-slate-900 antialiased selection:bg-emerald-100 selection:text-emerald-900">
        <div class="min-h-screen lg:grid lg:grid-cols-[1fr_1.05fr] xl:grid-cols-[1fr_1fr]">
            <!-- Form column -->
            <div class="flex min-h-screen flex-col bg-white">
                <header class="flex h-20 shrink-0 items-center justify-between gap-4 px-6 sm:px-10">
                    <a href="{{ url('/') }}" class="flex items-center gap-2.5 rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2" aria-label="Stoq home">
                        <svg viewBox="0 0 32 32" class="h-8 w-8" fill="none" aria-hidden="true">
                            <rect width="32" height="32" rx="9" fill="#047857" />
                            <path d="M9 12.25 16 8.5l7 3.75v7.5L16 23.5l-7-3.75v-7.5Z" stroke="#fff" stroke-width="1.7" stroke-linejoin="round" />
                            <path d="m9 12.25 7 3.75 7-3.75M16 16v7.5" stroke="#fff" stroke-width="1.7" stroke-linejoin="round" />
                        </svg>
                        <span class="text-[1.35rem] font-semibold tracking-tight text-slate-900">stoq</span>
                    </a>

                    <a href="{{ url('/') }}" class="group inline-flex items-center gap-1.5 rounded-lg text-sm font-medium text-slate-600 transition-colors hover:text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2">
                        <svg class="h-4 w-4 transition-transform duration-150 group-hover:-translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                        Back to home
                    </a>
                </header>

                <main class="flex flex-1 items-center justify-center px-6 py-10 sm:px-10">
                    <div class="w-full max-w-[26rem]">
                        {{ $slot }}
                    </div>
                </main>

                <footer class="shrink-0 px-6 pb-8 sm:px-10">
                    <div class="flex flex-col gap-3 border-t border-slate-100 pt-6 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
                        <p>&copy; {{ date('Y') }} Stoq. All rights reserved.</p>
                        <div class="flex items-center gap-5">
                            <a href="#" class="transition-colors hover:text-slate-800">Privacy</a>
                            <a href="#" class="transition-colors hover:text-slate-800">Terms</a>
                            <a href="mailto:hello@stoq.app" class="transition-colors hover:text-slate-800">Help</a>
                        </div>
                    </div>
                </footer>
            </div>

            <!-- Brand column -->
            <aside class="relative hidden bg-slate-950 lg:sticky lg:top-0 lg:flex lg:min-h-screen lg:flex-col lg:justify-between lg:p-10 xl:p-14">
                <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
                    <div class="absolute inset-0 opacity-[0.08]" style="background-image: linear-gradient(to right, #fff 1px, transparent 1px), linear-gradient(to bottom, #fff 1px, transparent 1px); background-size: 52px 52px; -webkit-mask-image: radial-gradient(ellipse 80% 75% at 50% 45%, black, transparent); mask-image: radial-gradient(ellipse 80% 75% at 50% 45%, black, transparent);"></div>
                    <div class="absolute -right-24 top-1/4 h-[26rem] w-[26rem] rounded-full bg-emerald-500/20 blur-3xl"></div>
                    <div class="absolute -bottom-32 -left-24 h-[24rem] w-[24rem] rounded-full bg-emerald-400/10 blur-3xl"></div>
                </div>

                <div class="relative flex items-center gap-2.5">
                    <svg viewBox="0 0 32 32" class="h-8 w-8" fill="none" aria-hidden="true">
                        <rect width="32" height="32" rx="9" fill="#047857" />
                        <path d="M9 12.25 16 8.5l7 3.75v7.5L16 23.5l-7-3.75v-7.5Z" stroke="#fff" stroke-width="1.7" stroke-linejoin="round" />
                        <path d="m9 12.25 7 3.75 7-3.75M16 16v7.5" stroke="#fff" stroke-width="1.7" stroke-linejoin="round" />
                    </svg>
                    <span class="text-[1.35rem] font-semibold tracking-tight text-white">stoq</span>
                    <span class="ml-auto rounded-full border border-white/10 bg-white/5 px-3 py-1 text-[11px] font-medium text-slate-300">Inventory for small shops</span>
                </div>

                <div class="relative my-10 max-w-lg">
                    <p class="text-sm font-semibold uppercase tracking-[0.16em] text-emerald-300">Welcome back</p>
                    <h2 class="mt-4 text-balance text-4xl font-semibold leading-[1.08] tracking-tight text-white xl:text-[2.75rem]">Know what&rsquo;s on the shelf. Before you run out.</h2>
                    <p class="mt-5 text-base leading-7 text-slate-300">Live stock levels, low-stock alerts, and reorder suggestions in one calm view. Sign in and pick up where you left off.</p>

                    <div class="mt-8 rounded-2xl border border-white/10 bg-white/[0.04] p-4 backdrop-blur-sm" aria-hidden="true">
                        <div class="flex items-center justify-between">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-300">Needs attention</p>
                            <span class="rounded-full bg-amber-400/10 px-2 py-0.5 text-[10px] font-semibold text-amber-300 ring-1 ring-amber-400/20">3 items</span>
                        </div>
                        <div class="mt-3.5 space-y-3">
                            <div class="flex items-center gap-2.5">
                                <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-amber-400"></span>
                                <p class="min-w-0 flex-1 truncate text-[11px] font-medium text-slate-200">Linen Tote Bag</p>
                                <p class="text-[10px] text-slate-400">4 left</p>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-rose-400"></span>
                                <p class="min-w-0 flex-1 truncate text-[11px] font-medium text-slate-200">Ceramic Mug 350ml</p>
                                <p class="text-[10px] text-slate-400">2 left</p>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-amber-400"></span>
                                <p class="min-w-0 flex-1 truncate text-[11px] font-medium text-slate-200">Organic Coffee 1kg</p>
                                <p class="text-[10px] text-slate-400">7 left</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <figure class="rounded-2xl border border-white/10 bg-white/[0.04] p-5">
                        <div class="flex gap-0.5 text-amber-400" role="img" aria-label="Rated 5 out of 5">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                        </div>
                        <blockquote class="mt-3 text-sm leading-6 text-slate-200">&ldquo;Stock takes went from a full Sunday to about twenty minutes. That alone paid for the year.&rdquo;</blockquote>
                        <figcaption class="mt-3 flex items-center gap-2.5">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500/15 text-[10px] font-semibold text-emerald-200 ring-1 ring-emerald-400/20" aria-hidden="true">DO</span>
                            <div>
                                <p class="text-xs font-semibold text-white">Daniel Okafor</p>
                                <p class="text-[11px] text-slate-400">Co-owner, Brew &amp; Co.</p>
                            </div>
                        </figcaption>
                    </figure>

                    <p class="mt-5 flex items-center justify-between text-[11px] text-slate-500">
                        <span>&copy; {{ date('Y') }} Stoq</span>
                        <span>Built for independent shops</span>
                    </p>
                </div>
            </aside>
        </div>
    </body>
</html>
