<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth" style="color-scheme: light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#F8FAFC">

        <title>{{ __('Orbit — a multi-tenant SaaS starter kit') }}</title>
        <meta name="description" content="{{ __('A Laravel starter kit with authentication, workspaces, roles and a replaceable dashboard. Placeholder content only.') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            summary::-webkit-details-marker { display: none; }
        </style>
    </head>
    <body class="relative overflow-x-hidden bg-canvas text-foreground">
        <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-lg focus:border focus:border-line focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-foreground focus:shadow-glass">
            {{ __('Skip to content') }}
        </a>

        <!-- Header -->
        <header x-data="{ mobileOpen: false }" class="fixed inset-x-0 top-0 z-50 border-b border-white/70 bg-white/75 backdrop-blur-xl">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-6 px-5 sm:px-6 lg:px-8">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3 rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary" aria-label="{{ __('Starter kit home') }}">
                    <x-brand-mark class="h-9 w-9" />
                    <span class="text-lg font-extrabold tracking-tight">orbit</span>
                    <span class="hidden border-l border-line pl-3 text-[10px] font-bold uppercase tracking-[0.16em] text-muted-foreground sm:inline">Starter kit</span>
                </a>

                <nav class="hidden items-center gap-8 lg:flex" aria-label="Primary">
                    <a href="#features" class="text-xs font-bold uppercase tracking-[0.12em] text-muted-foreground transition-colors hover:text-primary">{{ __('Features') }}</a>
                    <a href="#stack" class="text-xs font-bold uppercase tracking-[0.12em] text-muted-foreground transition-colors hover:text-primary">{{ __('Stack') }}</a>
                    <a href="#faq" class="text-xs font-bold uppercase tracking-[0.12em] text-muted-foreground transition-colors hover:text-primary">FAQ</a>
                </nav>

                <div class="flex items-center gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="hidden px-3 py-2 text-sm font-semibold text-muted-foreground transition-colors hover:text-primary sm:inline-flex">{{ __('Dashboard') }}</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden px-3 py-2 text-sm font-semibold text-muted-foreground transition-colors hover:text-primary sm:inline-flex">{{ __('Sign in') }}</a>
                    @endauth
                    <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="btn-primary hidden !px-5 !py-2.5 sm:inline-flex">
                        {{ auth()->check() ? __('Open dashboard') : __('Get started') }}
                    </a>
                    <button type="button" @click="mobileOpen = !mobileOpen" :aria-expanded="mobileOpen" aria-controls="mobile-menu" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-line bg-white/80 text-foreground transition-colors hover:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary lg:hidden">
                        <span class="sr-only">{{ __('Toggle navigation') }}</span>
                        <svg x-show="!mobileOpen" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                        <svg x-show="mobileOpen" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>

            <div id="mobile-menu" x-show="mobileOpen" x-cloak x-transition.opacity.duration.150ms class="absolute inset-x-0 top-full border-b border-line bg-white/95 shadow-glass backdrop-blur-xl lg:hidden">
                <nav class="mx-auto flex max-w-7xl flex-col gap-1 px-5 py-4 sm:px-6" aria-label="Mobile">
                    <a href="#features" @click="mobileOpen = false" class="rounded-lg px-3 py-2.5 text-sm font-semibold text-muted-foreground hover:bg-muted hover:text-primary">{{ __('Features') }}</a>
                    <a href="#stack" @click="mobileOpen = false" class="rounded-lg px-3 py-2.5 text-sm font-semibold text-muted-foreground hover:bg-muted hover:text-primary">{{ __('Stack') }}</a>
                    <a href="#faq" @click="mobileOpen = false" class="rounded-lg px-3 py-2.5 text-sm font-semibold text-muted-foreground hover:bg-muted hover:text-primary">FAQ</a>
                    <div class="mt-2 flex flex-col gap-2 border-t border-line pt-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-3 py-2.5 text-sm font-semibold text-muted-foreground">{{ __('Dashboard') }}</a>
                        @else
                            <a href="{{ route('login') }}" class="px-3 py-2.5 text-sm font-semibold text-muted-foreground">{{ __('Sign in') }}</a>
                        @endauth
                        <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="btn-primary">{{ auth()->check() ? __('Open dashboard') : __('Get started') }}</a>
                    </div>
                </nav>
            </div>
        </header>

        <main id="main" class="pt-16">
            <!-- Hero -->
            <section class="relative overflow-hidden bg-brand-gradient">
                <div class="pointer-events-none absolute inset-0 bg-brand-sheen" aria-hidden="true"></div>
                <div class="pointer-events-none absolute -left-24 top-10 h-80 w-80 rounded-full border border-white/20 bg-white/10 backdrop-blur-sm" aria-hidden="true"></div>
                <div class="pointer-events-none absolute -right-20 bottom-0 h-96 w-96 rounded-full bg-white/10 blur-3xl" aria-hidden="true"></div>
                <div class="pointer-events-none absolute inset-0 opacity-[0.1]" style="background-image: linear-gradient(to right, #fff 1px, transparent 1px), linear-gradient(to bottom, #fff 1px, transparent 1px); background-size: 72px 72px;" aria-hidden="true"></div>

                <div class="relative mx-auto max-w-7xl px-5 pb-16 pt-16 text-white sm:px-6 sm:pb-20 sm:pt-24 lg:px-8">
                    <div class="mx-auto max-w-3xl text-center">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-white/85">{{ __('Multi-tenant SaaS starter kit') }}</p>
                        <h1 class="mt-6 text-5xl font-extrabold leading-[1.05] tracking-tight sm:text-6xl lg:text-[4.5rem]">
                            {{ __('Start with the structure.') }}<br>
                            <span class="text-[#BFDBFE]">{{ __('Build the product.') }}</span>
                        </h1>
                        <p class="mx-auto mt-6 max-w-2xl text-base leading-7 text-white/90">
                            {{ __('Authentication, workspaces, roles and a dashboard shell are already in place. The content is deliberately generic — swap it for your own in minutes.') }}
                        </p>

                        <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                            <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-accent px-6 py-3.5 text-sm font-bold text-black shadow-accent-glow transition-all duration-150 hover:bg-accent-dark active:translate-y-px focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-primary sm:w-auto">
                                {{ auth()->check() ? __('Open dashboard') : __('Create an account') }}
                                <span aria-hidden="true">&rarr;</span>
                            </a>
                            <a href="#features" class="inline-flex w-full items-center justify-center rounded-xl border border-white/40 bg-white/10 px-6 py-3.5 text-sm font-bold text-white backdrop-blur-md transition-all duration-150 hover:bg-white/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-primary sm:w-auto">{{ __('See what is included') }}</a>
                        </div>

                        <ul class="mt-7 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-[11px] font-bold uppercase tracking-[0.12em] text-white/85">
                            <li>Auth included</li>
                            <li>Workspace ready</li>
                            <li>Placeholder data</li>
                        </ul>
                    </div>

                    <!-- Preview -->
                    <div class="relative mx-auto mt-14 max-w-5xl sm:mt-16">
                        <div class="overflow-hidden rounded-2xl border border-white/40 bg-white/95 shadow-glass-lg backdrop-blur-xl" aria-hidden="true">
                            <div class="flex items-center gap-3 border-b border-line bg-white/70 px-4 py-2.5">
                                <div class="flex shrink-0 gap-1.5">
                                    <span class="h-2.5 w-2.5 rounded-full bg-line"></span>
                                    <span class="h-2.5 w-2.5 rounded-full bg-line"></span>
                                    <span class="h-2.5 w-2.5 rounded-full bg-line"></span>
                                </div>
                                <div class="mx-auto flex w-full max-w-[280px] items-center justify-center rounded-lg border border-line bg-white/80 px-3 py-1 text-[10px] font-medium text-muted-foreground">app.example.test/overview</div>
                                <div class="w-10 shrink-0"></div>
                            </div>

                            <div class="flex">
                                <aside class="hidden w-44 shrink-0 border-r border-white/10 bg-slate-900/95 p-4 backdrop-blur-xl md:block">
                                    <div class="flex items-center gap-2 font-extrabold text-white">
                                        <x-brand-mark class="h-6 w-6" />
                                        <span class="text-sm">orbit</span>
                                    </div>
                                    <div class="mt-6 rounded-xl border border-white/20 bg-white/10 p-2.5">
                                        <p class="truncate text-[11px] font-bold text-white">Acme Workspace</p>
                                        <p class="mt-0.5 text-[9px] font-semibold uppercase text-white/80">Demo · 3 tenants</p>
                                    </div>
                                    <div class="mt-5 space-y-1 text-[10px] font-bold uppercase tracking-[0.08em] text-white/80">
                                        <p class="rounded-lg border-l-2 border-primary bg-primary/25 px-2 py-1.5 text-white">Overview</p>
                                        <p class="px-2 py-1.5">Projects</p>
                                        <p class="px-2 py-1.5">Tasks</p>
                                        <p class="px-2 py-1.5">Tenants</p>
                                        <p class="px-2 py-1.5">Members</p>
                                    </div>
                                </aside>

                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between border-b border-line px-4 py-3 sm:px-5">
                                        <div>
                                            <p class="text-[9px] font-bold uppercase tracking-[0.12em] text-muted-foreground">Acme Workspace / Overview</p>
                                            <p class="mt-1 text-lg font-extrabold text-foreground">Good morning, Ada.</p>
                                        </div>
                                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-gradient-to-br from-secondary to-primary text-[10px] font-bold text-white">AL</span>
                                    </div>

                                    <div class="p-4 sm:p-5">
                                        <div class="grid grid-cols-3 gap-2.5">
                                            @foreach ([['Tenants', '12'], ['Members', '248'], ['Uptime', '99.9%']] as $stat)
                                                <div class="rounded-xl border border-line bg-white/80 p-3">
                                                    <p class="text-[8px] font-bold uppercase tracking-[0.1em] text-muted-foreground">{{ $stat[0] }}</p>
                                                    <p class="mt-1 text-2xl font-extrabold text-foreground">{{ $stat[1] }}</p>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="mt-3 grid gap-3 lg:grid-cols-5">
                                            <div class="rounded-xl border border-line bg-white/80 p-3.5 lg:col-span-3">
                                                <p class="text-sm font-extrabold text-foreground">Workspace activity</p>
                                                <svg viewBox="0 0 400 100" class="mt-3 h-20 w-full" preserveAspectRatio="none">
                                                    <g stroke="#E2E8F0" stroke-width="1" stroke-dasharray="3 5">
                                                        <line x1="0" y1="25" x2="400" y2="25" />
                                                        <line x1="0" y1="55" x2="400" y2="55" />
                                                        <line x1="0" y1="85" x2="400" y2="85" />
                                                    </g>
                                                    <path d="M0 82 C 50 76 70 66 110 70 C 150 74 180 50 220 56 C 260 62 290 34 330 40 C 365 46 385 24 400 28" fill="none" stroke="#2563EB" stroke-width="2.5" stroke-linecap="round" />
                                                </svg>
                                                <div class="mt-1 flex justify-between text-[8px] font-semibold text-muted-foreground"><span>MON</span><span>THU</span><span>SUN</span></div>
                                            </div>
                                            <div class="rounded-xl border border-line bg-white/80 p-3.5 lg:col-span-2">
                                                <p class="text-sm font-extrabold text-foreground">Needs attention</p>
                                                <div class="mt-3 space-y-2.5">
                                                    <p class="border-l-2 border-warning pl-2 text-[10px] font-medium text-muted-foreground">3 pending invites</p>
                                                    <p class="border-l-2 border-destructive pl-2 text-[10px] font-medium text-muted-foreground">Trial ending soon</p>
                                                    <p class="border-l-2 border-warning pl-2 text-[10px] font-medium text-muted-foreground">1 unverified domain</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features -->
            <section id="features" class="scroll-mt-16 border-y border-line bg-white/60">
                <div class="mx-auto max-w-7xl px-5 py-16 sm:px-6 sm:py-20 lg:px-8">
                    <div class="max-w-2xl">
                        <p class="eyebrow">{{ __('What is included') }}</p>
                        <h2 class="mt-4 text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl">{{ __('The boring parts, already done.') }}</h2>
                        <p class="mt-4 text-sm leading-7 text-muted-foreground">{{ __('A usable foundation with deliberately neutral content — ready to become whatever you are building.') }}</p>
                    </div>

                    <div class="mt-12 grid gap-4 border-t border-line sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ([
                            ['01', __('Workspaces'), __('Switch between tenants from a single shell. Isolation is the default.')],
                            ['02', __('Authentication'), __('Sign in, registration, password recovery and email verification are wired up.')],
                            ['03', __('Roles & access'), __('A place to attach permissions without rewriting your navigation.')],
                            ['04', __('Replaceable UI'), __('Every screen here is placeholder content you can delete without guilt.')],
                        ] as $feature)
                            <div class="glass-card p-6">
                                <p class="text-xs font-extrabold text-primary">{{ $feature[0] }}</p>
                                <h3 class="mt-4 text-xl font-extrabold text-foreground">{{ $feature[1] }}</h3>
                                <p class="mt-3 text-sm leading-6 text-muted-foreground">{{ $feature[2] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Stack -->
            <section id="stack" class="scroll-mt-16">
                <div class="mx-auto grid max-w-7xl gap-10 px-5 py-16 sm:px-6 sm:py-20 lg:grid-cols-[1fr_1.2fr] lg:px-8">
                    <div>
                        <p class="eyebrow">{{ __('Under the hood') }}</p>
                        <h2 class="mt-4 text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl">{{ __('Familiar tools.') }}</h2>
                        <p class="mt-4 text-sm leading-7 text-muted-foreground">{{ __('Nothing exotic. Change a component, run the tests, and keep moving.') }}</p>
                    </div>
                    <ul class="divide-y divide-line border-y border-line">
                        @foreach ([
                            ['Laravel', __('Application framework')],
                            ['Breeze', __('Authentication scaffolding')],
                            ['Tailwind CSS', __('Design tokens and utilities')],
                            ['Alpine.js', __('Lightweight interactivity')],
                            ['Pest', __('Feature and unit tests')],
                            ['Vite', __('Asset build')],
                        ] as $item)
                            <li class="flex items-baseline justify-between gap-4 py-4">
                                <span class="text-sm font-bold text-foreground">{{ $item[0] }}</span>
                                <span class="text-xs font-medium text-muted-foreground">{{ $item[1] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>

            <!-- FAQ -->
            <section id="faq" class="scroll-mt-16 border-t border-line bg-white/60">
                <div class="mx-auto max-w-3xl px-5 py-16 sm:px-6 sm:py-20">
                    <p class="eyebrow">FAQ</p>
                    <h2 class="mt-4 text-4xl font-extrabold tracking-tight sm:text-5xl">{{ __('Good questions.') }}</h2>

                    <div class="mt-8 divide-y divide-line border-y border-line">
                        @foreach ([
                            [__('Is this tied to a specific product?'), __('No. The names, metrics and records are placeholders meant to be replaced.')],
                            [__('Can I remove the dashboard content?'), __('Yes. It is one Blade file. Keep the layout and sidebar, or replace the lot.')],
                            [__('Where do I start?'), __('Create an account, look around, then change the placeholder names to your own.')],
                        ] as $faq)
                            <details class="group py-5">
                                <summary class="flex cursor-pointer items-center justify-between gap-4 text-sm font-bold text-foreground">{{ $faq[0] }}<span class="font-extrabold text-primary" aria-hidden="true">+</span></summary>
                                <p class="mt-3 pr-8 text-sm leading-6 text-muted-foreground">{{ $faq[1] }}</p>
                            </details>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Closing -->
            <section class="relative overflow-hidden bg-brand-gradient text-white">
                <div class="pointer-events-none absolute inset-0 bg-brand-sheen" aria-hidden="true"></div>
                <div class="relative mx-auto flex max-w-7xl flex-col gap-8 px-5 py-16 sm:px-6 sm:py-20 lg:flex-row lg:items-end lg:justify-between lg:px-8">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-white/80">{{ __('Ready when you are') }}</p>
                        <h2 class="mt-4 max-w-xl text-4xl font-extrabold leading-tight sm:text-5xl">{{ __('Make it yours.') }}</h2>
                    </div>
                    <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-6 py-3.5 text-sm font-bold text-primary shadow-glass transition-colors hover:bg-white/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-primary">
                        {{ auth()->check() ? __('Open dashboard') : __('Create an account') }}
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </section>
        </main>

        <footer class="border-t border-line bg-white/70">
            <div class="mx-auto max-w-7xl px-5 py-10 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary" aria-label="{{ __('Starter kit home') }}">
                            <x-brand-mark class="h-9 w-9" />
                            <span class="text-lg font-extrabold tracking-tight">orbit</span>
                        </a>
                        <p class="mt-4 max-w-xs text-sm leading-6 text-muted-foreground">{{ __('A multi-tenant starting point with placeholder content.') }}</p>
                    </div>
                    <nav class="flex gap-8 text-xs font-bold uppercase tracking-[0.1em] text-muted-foreground" aria-label="Footer">
                        <a href="#features" class="hover:text-primary">{{ __('Features') }}</a>
                        <a href="#stack" class="hover:text-primary">{{ __('Stack') }}</a>
                        <a href="#faq" class="hover:text-primary">FAQ</a>
                    </nav>
                </div>
                <p class="mt-10 border-t border-line pt-6 text-xs text-muted-foreground">&copy; {{ date('Y') }} Orbit. {{ __('A starter for new ideas.') }}</p>
            </div>
        </footer>
    </body>
</html>
