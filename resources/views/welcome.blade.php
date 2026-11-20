<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth" style="color-scheme: light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#F8FAFC">

        <title>{{ __('Orbit — multi-tenancy starter kit') }}</title>
        <meta name="description" content="{{ __('A Laravel starter kit with subdomain tenancy, workspaces, roles and permissions, activity logging and replaceable placeholder screens.') }}">

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
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary" aria-label="{{ __('Orbit home') }}">
                    <x-brand-mark class="h-9 w-9" />
                    <span class="text-lg font-extrabold tracking-tight">orbit</span>
                </a>

                <nav class="hidden items-center gap-8 lg:flex" aria-label="Primary">
                    <a href="#what" class="text-xs font-bold uppercase tracking-[0.12em] text-muted-foreground transition-colors hover:text-primary">{{ __('What it is') }}</a>
                    <a href="#stack" class="text-xs font-bold uppercase tracking-[0.12em] text-muted-foreground transition-colors hover:text-primary">{{ __('Stack') }}</a>
                    <a href="#faq" class="text-xs font-bold uppercase tracking-[0.12em] text-muted-foreground transition-colors hover:text-primary">FAQ</a>
                </nav>

                <div class="flex items-center gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="hidden px-3 py-2 text-sm font-semibold text-muted-foreground transition-colors hover:text-primary sm:inline-flex">{{ __('Dashboard') }}</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden px-3 py-2 text-sm font-semibold text-muted-foreground transition-colors hover:text-primary sm:inline-flex">{{ __('Sign in') }}</a>
                    @endauth
                    <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="btn-primary hidden !px-4 !py-2 sm:inline-flex">
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
                    <a href="#what" @click="mobileOpen = false" class="rounded-lg px-3 py-2.5 text-sm font-semibold text-muted-foreground hover:bg-muted hover:text-primary">{{ __('What it is') }}</a>
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
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-white/85">{{ __('Multi-tenancy starter kit') }}</p>
                        <h1 class="mt-6 text-5xl font-extrabold leading-[1.05] tracking-tight sm:text-6xl lg:text-[4.5rem]">
                            {{ __('Start with the structure.') }}<br>
                            <span class="text-[#BFDBFE]">{{ __('Build the product.') }}</span>
                        </h1>
                        <p class="mx-auto mt-6 max-w-2xl text-base leading-7 text-white/90">
                            {{ __('A Laravel application with real authentication, tenant subdomains, workspace-scoped roles and permissions, and an activity log. The dashboard content is deliberately placeholder so you can replace it without untangling demo data.') }}
                        </p>

                        <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                            <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-accent px-5 py-2.5 text-sm font-bold text-black shadow-accent-glow transition-all duration-150 hover:bg-accent-dark active:translate-y-px focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-primary sm:w-auto">
                                {{ auth()->check() ? __('Open dashboard') : __('Create an account') }}
                                <span aria-hidden="true">&rarr;</span>
                            </a>
                            <a href="#what" class="inline-flex w-full items-center justify-center rounded-lg border border-white/40 bg-white/10 px-5 py-2.5 text-sm font-bold text-white backdrop-blur-md transition-all duration-150 hover:bg-white/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-primary sm:w-auto">{{ __('See what is included') }}</a>
                        </div>

                        <ul class="mt-7 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-[11px] font-bold uppercase tracking-[0.12em] text-white/85">
                            <li>Subdomain tenancy</li>
                            <li>Roles &amp; permissions</li>
                            <li>Placeholder dashboard</li>
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
                                <div class="mx-auto flex w-full max-w-[280px] items-center justify-center rounded-lg border border-line bg-white/80 px-3 py-1 text-[10px] font-medium text-muted-foreground">workspace.saas.test/overview</div>
                                <div class="w-10 shrink-0"></div>
                            </div>

                            <div class="flex">
                                <aside class="hidden w-44 shrink-0 border-r border-white/10 bg-slate-900/95 p-4 backdrop-blur-xl md:block">
                                    <div class="flex items-center gap-2 font-extrabold text-white">
                                        <x-brand-mark class="h-6 w-6" />
                                        <span class="text-sm">orbit</span>
                                    </div>
                                    <div class="mt-6 rounded-xl border border-white/20 bg-white/10 p-2.5">
                                        <p class="truncate text-[11px] font-bold text-white">Placeholder Workspace</p>
                                        <p class="mt-0.5 text-[9px] font-semibold uppercase text-white/80">Tenant · Preview</p>
                                    </div>
                                    <div class="mt-5 space-y-1 text-[10px] font-bold uppercase tracking-[0.08em] text-white/80">
                                        <p class="rounded-lg border-l-2 border-primary bg-primary/25 px-2 py-1.5 text-white">Overview</p>
                                        <p class="px-2 py-1.5">Activity</p>
                                        <p class="px-2 py-1.5">Settings</p>
                                        <p class="pl-5 text-white/60">Profile</p>
                                        <p class="pl-5 text-white/60">Workspace</p>
                                        <p class="pl-5 text-white/60">Team Members</p>
                                    </div>
                                </aside>

                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between border-b border-line px-4 py-3 sm:px-5">
                                        <div>
                                            <p class="text-[9px] font-bold uppercase tracking-[0.12em] text-muted-foreground">Placeholder Workspace / Overview</p>
                                            <p class="mt-1 text-lg font-extrabold text-foreground">Hello, Ada.</p>
                                        </div>
                                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-gradient-to-br from-secondary to-primary text-[10px] font-bold text-white">AL</span>
                                    </div>

                                    <div class="p-4 sm:p-5">
                                        <div class="grid grid-cols-3 gap-2.5">
                                            @foreach ([['Workspace', 'Placeholder'], ['Account', 'Ada Lovelace'], ['Starter kit', 'Ready']] as $stat)
                                                <div class="rounded-xl border border-line bg-white/80 p-3">
                                                    <p class="text-[8px] font-bold uppercase tracking-[0.1em] text-muted-foreground">{{ $stat[0] }}</p>
                                                    <p class="mt-1 truncate text-sm font-extrabold text-foreground">{{ $stat[1] }}</p>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="mt-3 grid gap-3 lg:grid-cols-5">
                                            <div class="rounded-xl border border-line bg-white/80 p-3.5 lg:col-span-3">
                                                <p class="text-sm font-extrabold text-foreground">Shortcuts</p>
                                                <div class="mt-3 grid gap-1.5 sm:grid-cols-2">
                                                    <span class="rounded-lg border border-line bg-white px-2 py-1.5 text-[10px] font-semibold text-muted-foreground">Profile</span>
                                                    <span class="rounded-lg border border-line bg-white px-2 py-1.5 text-[10px] font-semibold text-muted-foreground">Workspace settings</span>
                                                    <span class="rounded-lg border border-line bg-white px-2 py-1.5 text-[10px] font-semibold text-muted-foreground">Team members</span>
                                                    <span class="rounded-lg border border-line bg-white px-2 py-1.5 text-[10px] font-semibold text-muted-foreground">Activity log</span>
                                                </div>
                                            </div>
                                            <div class="rounded-xl border border-line bg-white/80 p-3.5 lg:col-span-2">
                                                <p class="text-sm font-extrabold text-foreground">Recent activity</p>
                                                <div class="mt-3 space-y-2.5">
                                                    <p class="border-l-2 border-success pl-2 text-[10px] font-medium text-muted-foreground">Signed in · Ada</p>
                                                    <p class="border-l-2 border-warning pl-2 text-[10px] font-medium text-muted-foreground">Signed out · Grace</p>
                                                    <p class="border-l-2 border-success pl-2 text-[10px] font-medium text-muted-foreground">Signed in · Ada</p>
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

            <!-- What it is -->
            <section id="what" class="scroll-mt-16 border-y border-line bg-white/60">
                <div class="mx-auto max-w-7xl px-5 py-16 sm:px-6 sm:py-20 lg:px-8">
                    <div class="max-w-3xl">
                        <p class="eyebrow">{{ __('What this project is') }}</p>
                        <h2 class="mt-4 text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl">{{ __('A foundation, not a finished product.') }}</h2>
                        <p class="mt-4 text-sm leading-7 text-muted-foreground">
                            {{ __('This is a multi-tenancy starter kit. The infrastructure is working code; the names, cards and list items are placeholders you are meant to delete or replace. No billing, projects or CRM domain logic is included.') }}
                        </p>
                    </div>

                    <div class="mt-12 grid gap-4 border-t border-line sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ([
                            ['10', __('Tenant routing'), __('Subdomains are created at signup. Custom domains can be added later in workspace settings and stored in the domains table.')],
                            ['02', __('Authentication'), __('Registration creates a tenant and its owner. Login, password reset, confirmation and email verification flows come from Breeze.')],
                            ['03', __('Scoped RBAC'), __('Spatie Permission roles and permissions are scoped per tenant, with a protected Admin role and a group-based permission matrix.')],
                            ['04', __('Settings shell'), __('Profile, workspace details, team members, roles and permissions live under one permission-aware settings navigation.')],
                            ['05', __('Activity log'), __('Spatie Activitylog records sign-ins and sign-outs. The activity page filters events without adding fake KPI cards.')],
                            ['06', __('Placeholder UI'), __('Dashboard cards, metric values and preview copy are intentionally generic so nothing pretends to be real product data.')],
                        ] as $feature)
                            <div class="glass-card p-5">
                                <p class="text-xs font-extrabold text-primary">{{ $feature[0] }}</p>
                                <h3 class="mt-3 text-lg font-extrabold text-foreground">{{ $feature[1] }}</h3>
                                <p class="mt-2 text-sm leading-6 text-muted-foreground">{{ $feature[2] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Stack -->
            <section id="stack" class="scroll-mt-16">
                <div class="mx-auto grid max-w-7xl gap-10 px-5 py-16 sm:px-6 sm:py-20 lg:grid-cols-[1fr_1.2fr] lg:px-8">
                    <div>
                        <p class="eyebrow">{{ __('Tech & packages') }}</p>
                        <h2 class="mt-4 text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl">{{ __('The tools behind it.') }}</h2>
                        <p class="mt-4 text-sm leading-7 text-muted-foreground">
                            {{ __('Installed versions are pinned in composer.json and package.json. The list below explains what each dependency does in this starter.') }}
                        </p>
                    </div>
                    <ul class="divide-y divide-line border-y border-line">
                        @foreach ([
                            ['PHP 8.3+', __('Runtime (verified on PHP 8.4)')],
                            ['Laravel 13', __('Application framework, routing, Eloquent, validation and testing')],
                            ['stancl/tenancy 3', __('Single-database tenancy, domain/subdomain identification and tenant scoping')],
                            ['spatie/laravel-permission 8', __('Roles and permissions scoped by tenant team ID')],
                            ['spatie/laravel-activitylog 5', __('Authentication event logging and activity filtering')],
                            ['laravel/breeze 2', __('Blade authentication scaffolding used for the auth screens')],
                            ['Tailwind CSS 3', __('Utility styling with the project’s shared component classes')],
                            ['Alpine.js 3', __('Small UI interactions: menus, dialogs and collapsible settings navigation')],
                            ['Vite 8', __('Frontend asset build')],
                            ['Pest 5', __('Feature tests for tenancy, RBAC, settings and authentication')],
                            ['Laravel Pint & Boost', __('Code formatting and AI-assisted Laravel development tooling')],
                        ] as $item)
                            <li class="flex flex-col gap-0.5 py-3.5 sm:flex-row sm:items-baseline sm:justify-between sm:gap-4">
                                <span class="text-sm font-bold text-foreground">{{ $item[0] }}</span>
                                <span class="text-xs leading-5 text-muted-foreground sm:text-right">{{ $item[1] }}</span>
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
                            [__('Is any of this real product data?'), __('No. The workspace names, dashboard labels and preview values are placeholders. Authentication, tenancy, permissions and activity records are real.')],
                            [__('Is a custom domain required at signup?'), __('No. Registration asks only for a subdomain. Add a custom domain later from Workspace settings if you need one.')],
                            [__('How is tenant data separated?'), __('This starter uses one database. Tenant-owned rows carry a tenant_id, and Spatie permission teams are scoped with the same tenant key.')],
                            [__('Can I remove the dashboard content?'), __('Yes. The dashboard, landing page and sidebar are ordinary Blade files. Keep the parts you need and replace the rest.')],
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
                    <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-5 py-2.5 text-sm font-bold text-primary shadow-glass transition-colors hover:bg-white/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-primary">
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
                        <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary" aria-label="{{ __('Orbit home') }}">
                            <x-brand-mark class="h-9 w-9" />
                            <span class="text-lg font-extrabold tracking-tight">orbit</span>
                        </a>
                        <p class="mt-4 max-w-xs text-sm leading-6 text-muted-foreground">{{ __('A multi-tenancy starting point with placeholder screens and real tenant infrastructure.') }}</p>
                    </div>
                    <nav class="flex gap-8 text-xs font-bold uppercase tracking-[0.1em] text-muted-foreground" aria-label="Footer">
                        <a href="#what" class="hover:text-primary">{{ __('What it is') }}</a>
                        <a href="#stack" class="hover:text-primary">{{ __('Stack') }}</a>
                        <a href="#faq" class="hover:text-primary">FAQ</a>
                    </nav>
                </div>
                <p class="mt-10 border-t border-line pt-6 text-xs text-muted-foreground">&copy; {{ date('Y') }} Orbit. {{ __('A starter for new ideas.') }}</p>
            </div>
        </footer>
    </body>
</html>
