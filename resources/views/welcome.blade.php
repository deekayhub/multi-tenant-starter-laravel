<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth" style="color-scheme: light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#ffffff">

        <title>Stoq — Inventory management for small shops</title>
        <meta name="description" content="Stoq is the inventory app for small shops. Track stock in real time, get low-stock alerts before you sell out, and turn reorders into purchase orders in a couple of clicks.">

        <meta property="og:type" content="website">
        <meta property="og:site_name" content="Stoq">
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:title" content="Stoq — Inventory management for small shops">
        <meta property="og:description" content="Real-time stock levels, low-stock alerts, and one-click purchase orders. Built for shops that would rather be selling than counting.">
        <meta name="twitter:card" content="summary">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&amp;display=swap" rel="stylesheet" />
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak] { display: none !important; }

            .landing {
                font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif;
                font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11';
            }

            summary::-webkit-details-marker { display: none; }

            @media (prefers-reduced-motion: reduce) {
                html { scroll-behavior: auto; }
                *, *::before, *::after {
                    animation-duration: 0.01ms !important;
                    animation-iteration-count: 1 !important;
                    transition-duration: 0.01ms !important;
                }
            }
        </style>
    </head>
    <body class="landing bg-white text-slate-900 antialiased selection:bg-emerald-100 selection:text-emerald-900">

        <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-slate-900 focus:shadow-lg focus:ring-2 focus:ring-emerald-600">
            Skip to content
        </a>

        <!-- Header -->
        <header x-data="{ mobileOpen: false }" class="fixed inset-x-0 top-0 z-50 border-b border-slate-200/70 bg-white/90 backdrop-blur-md">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-6 px-5 sm:px-6 lg:px-8">
                <a href="{{ url('/') }}" class="group flex shrink-0 items-center gap-2.5 rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2" aria-label="Stoq home">
                    <svg viewBox="0 0 32 32" class="h-8 w-8" fill="none" aria-hidden="true">
                        <rect width="32" height="32" rx="9" fill="#047857" />
                        <path d="M9 12.25 16 8.5l7 3.75v7.5L16 23.5l-7-3.75v-7.5Z" stroke="#fff" stroke-width="1.7" stroke-linejoin="round" />
                        <path d="m9 12.25 7 3.75 7-3.75M16 16v7.5" stroke="#fff" stroke-width="1.7" stroke-linejoin="round" />
                    </svg>
                    <span class="text-[1.35rem] font-semibold tracking-tight text-slate-900">stoq</span>
                </a>

                <nav class="hidden items-center gap-8 lg:flex" aria-label="Primary">
                    <a href="#features" class="text-sm font-medium text-slate-600 transition-colors hover:text-slate-900">Features</a>
                    <a href="#how-it-works" class="text-sm font-medium text-slate-600 transition-colors hover:text-slate-900">How it works</a>
                    <a href="#pricing" class="text-sm font-medium text-slate-600 transition-colors hover:text-slate-900">Pricing</a>
                    <a href="#faq" class="text-sm font-medium text-slate-600 transition-colors hover:text-slate-900">FAQ</a>
                </nav>

                <div class="flex items-center gap-2 sm:gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="hidden rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 transition-colors hover:text-slate-900 sm:inline-flex">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 transition-colors hover:text-slate-900 sm:inline-flex">Sign in</a>
                    @endauth
                    <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-700 px-3.5 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-emerald-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2">
                        {{ auth()->check() ? 'Go to app' : 'Start free' }}
                    </a>
                    <button type="button" @click="mobileOpen = !mobileOpen" :aria-expanded="mobileOpen" aria-expanded="false" aria-controls="mobile-menu" class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-slate-700 ring-1 ring-slate-200 transition-colors hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 lg:hidden">
                        <span class="sr-only">Toggle navigation</span>
                        <svg x-show="!mobileOpen" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true">
                            <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <svg x-show="mobileOpen" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true">
                            <path d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div id="mobile-menu" x-show="mobileOpen" x-cloak x-transition.opacity.duration.150ms class="absolute inset-x-0 top-full border-b border-slate-200 bg-white shadow-lg lg:hidden">
                <nav class="mx-auto flex max-w-7xl flex-col gap-1 px-5 py-4 sm:px-6" aria-label="Mobile">
                    <a href="#features" @click="mobileOpen = false" class="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Features</a>
                    <a href="#how-it-works" @click="mobileOpen = false" class="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">How it works</a>
                    <a href="#pricing" @click="mobileOpen = false" class="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Pricing</a>
                    <a href="#faq" @click="mobileOpen = false" class="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">FAQ</a>
                    <div class="mt-2 flex flex-col gap-2 border-t border-slate-100 pt-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Sign in</a>
                        @endauth
                        <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="rounded-lg bg-emerald-700 px-3 py-2.5 text-center text-sm font-semibold text-white hover:bg-emerald-800">
                            {{ auth()->check() ? 'Go to app' : 'Start free' }}
                        </a>
                    </div>
                </nav>
            </div>
        </header>

        <main id="main" class="pt-16">
            <!-- Hero -->
            <section class="relative overflow-hidden">
                <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[560px]" style="background-image: radial-gradient(60% 55% at 50% 0%, rgba(5, 150, 105, 0.12), transparent 72%);" aria-hidden="true"></div>
                <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[640px] opacity-60" style="background-image: linear-gradient(to right, rgba(15, 23, 42, 0.045) 1px, transparent 1px), linear-gradient(to bottom, rgba(15, 23, 42, 0.045) 1px, transparent 1px); background-size: 56px 56px; -webkit-mask-image: radial-gradient(ellipse 62% 55% at 50% 0%, black, transparent); mask-image: radial-gradient(ellipse 62% 55% at 50% 0%, black, transparent);" aria-hidden="true"></div>

                <div class="relative mx-auto max-w-7xl px-5 pb-16 pt-16 sm:px-6 sm:pb-20 sm:pt-20 lg:px-8 lg:pt-24">
                    <div class="mx-auto max-w-3xl text-center">
                        <a href="#features" class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50/80 py-1 pl-1.5 pr-3 text-xs font-medium text-emerald-800 transition-colors hover:bg-emerald-50">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-2 py-0.5 text-[11px] font-semibold text-emerald-700 shadow-sm ring-1 ring-emerald-100">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500" aria-hidden="true"></span>
                                New
                            </span>
                            Barcode counts are now on every plan
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>

                        <h1 class="mt-6 text-balance text-4xl font-semibold leading-[1.06] tracking-tight text-slate-900 sm:text-5xl lg:text-[3.75rem]">
                            Never sell out of your <span class="text-emerald-700">best sellers</span> again.
                        </h1>

                        <p class="mx-auto mt-5 max-w-2xl text-balance text-base leading-7 text-slate-600 sm:text-lg sm:leading-8">
                            Stoq is the inventory app for small shops. Track stock in real time, get alerted before you run low, and turn reorders into purchase orders in a couple of clicks.
                        </p>

                        <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                            <a href="{{ route('register') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-700 px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-emerald-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2 sm:w-auto">
                                Start your free trial
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </a>
                            <a href="#how-it-works" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-white px-6 py-3.5 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-slate-300 transition-colors hover:bg-slate-50 hover:text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2 sm:w-auto">
                                <svg class="h-4 w-4 text-emerald-700" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z" clip-rule="evenodd" />
                                </svg>
                                See how it works
                            </a>
                        </div>

                        <ul class="mt-6 flex flex-wrap items-center justify-center gap-x-5 gap-y-2 text-xs font-medium text-slate-600">
                            <li class="flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>
                                14-day free trial
                            </li>
                            <li class="flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>
                                No credit card required
                            </li>
                            <li class="flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>
                                Set up in 10 minutes
                            </li>
                        </ul>
                    </div>

                    <!-- Product preview -->
                    <div class="relative mx-auto mt-14 max-w-5xl sm:mt-16">
                        <div class="absolute -inset-x-6 -bottom-8 top-24 -z-10 rounded-[2rem] bg-emerald-600/10 blur-3xl" aria-hidden="true"></div>

                        <div class="relative overflow-hidden rounded-2xl bg-white text-left shadow-[0_40px_100px_-30px_rgba(15,23,42,0.35)] ring-1 ring-slate-200" aria-hidden="true">
                            <!-- Browser chrome -->
                            <div class="flex items-center gap-3 border-b border-slate-200 bg-slate-50 px-4 py-2.5">
                                <div class="flex shrink-0 gap-1.5">
                                    <span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>
                                    <span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>
                                    <span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>
                                </div>
                                <div class="mx-auto flex w-full max-w-[280px] items-center justify-center gap-1.5 rounded-md bg-white px-3 py-1 text-[10px] font-medium text-slate-500 ring-1 ring-slate-200">
                                    <svg class="h-3 w-3 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                    </svg>
                                    app.stoq.com/overview
                                </div>
                                <div class="w-10 shrink-0"></div>
                            </div>

                            <div class="flex">
                                <!-- App sidebar -->
                                <aside class="hidden w-48 shrink-0 border-r border-slate-200 bg-slate-50/70 p-4 md:block">
                                    <div class="flex items-center gap-2 px-2">
                                        <svg viewBox="0 0 32 32" class="h-5 w-5" fill="none">
                                            <rect width="32" height="32" rx="9" fill="#047857" />
                                            <path d="M9 12.25 16 8.5l7 3.75v7.5L16 23.5l-7-3.75v-7.5Z" stroke="#fff" stroke-width="2" stroke-linejoin="round" />
                                            <path d="m9 12.25 7 3.75 7-3.75M16 16v7.5" stroke="#fff" stroke-width="2" stroke-linejoin="round" />
                                        </svg>
                                        <span class="text-sm font-semibold tracking-tight text-slate-900">stoq</span>
                                    </div>

                                    <div class="mt-5 space-y-0.5">
                                        <div class="flex items-center gap-2 rounded-md bg-white px-2.5 py-1.5 text-[11px] font-semibold text-slate-900 shadow-sm ring-1 ring-slate-200">
                                            <svg class="h-3.5 w-3.5 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 21.75h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" /></svg>
                                            Overview
                                        </div>
                                        <div class="flex items-center gap-2 rounded-md px-2.5 py-1.5 text-[11px] font-medium text-slate-500">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5 12 2.25 3.75 7.5m16.5 0-8.25 4.5m8.25-4.5v9L12 21.75m0-9.75L3.75 7.5m8.25 4.5v9.75m0-9.75L3.75 7.5v9L12 21.75" /></svg>
                                            Products
                                        </div>
                                        <div class="flex items-center gap-2 rounded-md px-2.5 py-1.5 text-[11px] font-medium text-slate-500">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" /></svg>
                                            Stock
                                        </div>
                                        <div class="flex items-center gap-2 rounded-md px-2.5 py-1.5 text-[11px] font-medium text-slate-500">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                                            Orders
                                        </div>
                                        <div class="flex items-center gap-2 rounded-md px-2.5 py-1.5 text-[11px] font-medium text-slate-500">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" /></svg>
                                            Suppliers
                                        </div>
                                    </div>

                                    <div class="mt-6 rounded-lg border border-slate-200 bg-white p-2.5">
                                        <div class="flex items-center justify-between text-[10px] font-semibold text-slate-700">
                                            <span>Free trial</span>
                                            <span class="text-emerald-700">9 days left</span>
                                        </div>
                                        <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-100">
                                            <div class="h-full w-2/3 rounded-full bg-emerald-600"></div>
                                        </div>
                                    </div>
                                </aside>

                                <!-- App content -->
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-3 border-b border-slate-200 px-4 py-3 sm:px-5">
                                        <div>
                                            <p class="text-[13px] font-semibold text-slate-900">Overview</p>
                                            <p class="text-[10px] text-slate-500">Tue, 18 Nov · All locations</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="hidden items-center gap-1.5 rounded-md bg-slate-50 px-2.5 py-1.5 text-[10px] text-slate-500 ring-1 ring-slate-200 sm:flex">
                                                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                                                Search products…
                                            </div>
                                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-100 text-[10px] font-semibold text-emerald-800 ring-1 ring-emerald-200">MC</span>
                                        </div>
                                    </div>

                                    <div class="p-4 sm:p-5">
                                        <!-- Stats -->
                                        <div class="grid grid-cols-3 gap-2.5">
                                            <div class="rounded-xl border border-slate-200 bg-white p-3">
                                                <p class="text-[9px] font-semibold uppercase tracking-wide text-slate-500">Stock value</p>
                                                <p class="mt-1 text-lg font-semibold tracking-tight text-slate-900">$48,290</p>
                                                <p class="mt-0.5 flex items-center gap-1 text-[9px] font-medium text-emerald-700">
                                                    <svg class="h-2.5 w-2.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 4l8 10h-5v6H9v-6H4l8-10Z" /></svg>
                                                    8.2% this month
                                                </p>
                                            </div>
                                            <div class="rounded-xl border border-slate-200 bg-white p-3">
                                                <p class="text-[9px] font-semibold uppercase tracking-wide text-slate-500">Items tracked</p>
                                                <p class="mt-1 text-lg font-semibold tracking-tight text-slate-900">1,284</p>
                                                <p class="mt-0.5 text-[9px] font-medium text-slate-500">+36 this week</p>
                                            </div>
                                            <div class="rounded-xl border border-amber-200 bg-amber-50/60 p-3">
                                                <p class="text-[9px] font-semibold uppercase tracking-wide text-amber-700">Low stock</p>
                                                <p class="mt-1 text-lg font-semibold tracking-tight text-slate-900">6 items</p>
                                                <p class="mt-0.5 text-[9px] font-medium text-amber-700">Needs attention</p>
                                            </div>
                                        </div>

                                        <div class="mt-3 grid gap-3 lg:grid-cols-5">
                                            <!-- Chart -->
                                            <div class="rounded-xl border border-slate-200 bg-white p-3.5 lg:col-span-3">
                                                <div class="flex items-center justify-between">
                                                    <p class="text-[11px] font-semibold text-slate-900">Stock value</p>
                                                    <span class="rounded-md bg-slate-50 px-1.5 py-0.5 text-[9px] font-medium text-slate-500 ring-1 ring-slate-200">Last 30 days</span>
                                                </div>
                                                <svg viewBox="0 0 560 150" class="mt-3 h-24 w-full" preserveAspectRatio="none" aria-hidden="true">
                                                    <defs>
                                                        <linearGradient id="stockArea" x1="0" y1="0" x2="0" y2="1">
                                                            <stop offset="0%" stop-color="#10b981" stop-opacity="0.24" />
                                                            <stop offset="100%" stop-color="#10b981" stop-opacity="0" />
                                                        </linearGradient>
                                                    </defs>
                                                    <g stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4 6">
                                                        <line x1="0" y1="36" x2="560" y2="36" />
                                                        <line x1="0" y1="76" x2="560" y2="76" />
                                                        <line x1="0" y1="116" x2="560" y2="116" />
                                                    </g>
                                                    <path d="M0 122 C 36 118 56 108 84 110 C 112 112 132 92 160 96 C 188 100 208 72 236 78 C 264 84 284 58 312 64 C 340 70 360 44 388 50 C 416 56 446 34 474 38 C 502 42 530 24 560 26 L560 150 L0 150 Z" fill="url(#stockArea)" />
                                                    <path d="M0 122 C 36 118 56 108 84 110 C 112 112 132 92 160 96 C 188 100 208 72 236 78 C 264 84 284 58 312 64 C 340 70 360 44 388 50 C 416 56 446 34 474 38 C 502 42 530 24 560 26" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" />
                                                </svg>
                                                <div class="mt-1 flex justify-between text-[9px] text-slate-400">
                                                    <span>1 Nov</span>
                                                    <span>15 Nov</span>
                                                    <span>30 Nov</span>
                                                </div>
                                            </div>

                                            <!-- Needs attention -->
                                            <div class="rounded-xl border border-slate-200 bg-white p-3.5 lg:col-span-2">
                                                <div class="flex items-center justify-between">
                                                    <p class="text-[11px] font-semibold text-slate-900">Needs attention</p>
                                                    <span class="text-[9px] font-medium text-emerald-700">View all</span>
                                                </div>
                                                <div class="mt-2.5 space-y-2">
                                                    <div class="flex items-center gap-2">
                                                        <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-amber-500"></span>
                                                        <div class="min-w-0 flex-1">
                                                            <p class="truncate text-[10px] font-medium text-slate-800">Linen Tote Bag</p>
                                                            <p class="text-[9px] text-slate-500">4 left · reorder at 10</p>
                                                        </div>
                                                        <span class="rounded bg-emerald-50 px-1.5 py-0.5 text-[8px] font-semibold text-emerald-700 ring-1 ring-emerald-100">Reorder</span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-rose-500"></span>
                                                        <div class="min-w-0 flex-1">
                                                            <p class="truncate text-[10px] font-medium text-slate-800">Ceramic Mug 350ml</p>
                                                            <p class="text-[9px] text-slate-500">2 left · reorder at 12</p>
                                                        </div>
                                                        <span class="rounded bg-emerald-50 px-1.5 py-0.5 text-[8px] font-semibold text-emerald-700 ring-1 ring-emerald-100">Reorder</span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-amber-500"></span>
                                                        <div class="min-w-0 flex-1">
                                                            <p class="truncate text-[10px] font-medium text-slate-800">Organic Coffee 1kg</p>
                                                            <p class="text-[9px] text-slate-500">7 left · reorder at 15</p>
                                                        </div>
                                                        <span class="rounded bg-emerald-50 px-1.5 py-0.5 text-[8px] font-semibold text-emerald-700 ring-1 ring-emerald-100">Reorder</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Inventory table -->
                                        <div class="mt-3 overflow-hidden rounded-xl border border-slate-200 bg-white">
                                            <div class="flex items-center justify-between border-b border-slate-100 px-3.5 py-2">
                                                <p class="text-[11px] font-semibold text-slate-900">Inventory</p>
                                                <span class="text-[9px] text-slate-500">1,284 items</span>
                                            </div>
                                            <div class="grid grid-cols-12 gap-2 bg-slate-50/70 px-3.5 py-1.5 text-[8px] font-semibold uppercase tracking-wide text-slate-500">
                                                <span class="col-span-6 md:col-span-5">Product</span>
                                                <span class="hidden md:col-span-2 md:block">SKU</span>
                                                <span class="col-span-3 text-right md:col-span-2">On hand</span>
                                                <span class="col-span-3 text-right">Status</span>
                                            </div>
                                            <div class="divide-y divide-slate-100">
                                                <div class="grid grid-cols-12 items-center gap-2 px-3.5 py-2">
                                                    <span class="col-span-6 truncate text-[10px] font-medium text-slate-800 md:col-span-5">Linen Tote Bag</span>
                                                    <span class="hidden text-[10px] text-slate-500 md:col-span-2 md:block">TOTE-014</span>
                                                    <span class="col-span-3 text-right text-[10px] font-medium tabular-nums text-slate-700 md:col-span-2">4</span>
                                                    <span class="col-span-3 flex justify-end"><span class="rounded-full bg-amber-50 px-1.5 py-0.5 text-[8px] font-semibold text-amber-700 ring-1 ring-amber-200">Low stock</span></span>
                                                </div>
                                                <div class="grid grid-cols-12 items-center gap-2 px-3.5 py-2">
                                                    <span class="col-span-6 truncate text-[10px] font-medium text-slate-800 md:col-span-5">Ceramic Mug 350ml</span>
                                                    <span class="hidden text-[10px] text-slate-500 md:col-span-2 md:block">MUG-021</span>
                                                    <span class="col-span-3 text-right text-[10px] font-medium tabular-nums text-slate-700 md:col-span-2">2</span>
                                                    <span class="col-span-3 flex justify-end"><span class="rounded-full bg-rose-50 px-1.5 py-0.5 text-[8px] font-semibold text-rose-700 ring-1 ring-rose-200">Critical</span></span>
                                                </div>
                                                <div class="grid grid-cols-12 items-center gap-2 px-3.5 py-2">
                                                    <span class="col-span-6 truncate text-[10px] font-medium text-slate-800 md:col-span-5">Organic Coffee 1kg</span>
                                                    <span class="hidden text-[10px] text-slate-500 md:col-span-2 md:block">COF-108</span>
                                                    <span class="col-span-3 text-right text-[10px] font-medium tabular-nums text-slate-700 md:col-span-2">18</span>
                                                    <span class="col-span-3 flex justify-end"><span class="rounded-full bg-emerald-50 px-1.5 py-0.5 text-[8px] font-semibold text-emerald-700 ring-1 ring-emerald-200">In stock</span></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -bottom-5 left-4 hidden items-center gap-2.5 rounded-xl bg-white px-3.5 py-2.5 shadow-lg ring-1 ring-slate-200 sm:flex lg:left-8" aria-hidden="true">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m4.5 12.75 6 6 9-13.5" /></svg>
                            </span>
                            <span class="text-[11px] font-semibold text-slate-900">Stock synced</span>
                            <span class="text-[10px] text-slate-500">just now</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Social proof -->
            <section aria-label="Shops using Stoq" class="border-y border-slate-200 bg-slate-50/60">
                <div class="mx-auto max-w-7xl px-5 py-10 sm:px-6 lg:px-8">
                    <p class="text-center text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">Trusted by 2,000+ independent shops</p>
                    <ul class="mt-7 grid grid-cols-2 items-center gap-x-6 gap-y-6 sm:grid-cols-3 lg:grid-cols-5">
                        <li class="flex items-center justify-center gap-2 text-slate-500">
                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 19 6.5-11L15 19H3Z" /><path d="m14 14 3.5-6L21 14h-7Z" /></svg>
                            <span class="text-sm font-semibold tracking-tight">Northwind Goods</span>
                        </li>
                        <li class="flex items-center justify-center gap-2 text-slate-500">
                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v18M12 8l5-3M12 12l-5-3M12 16l5-3" /><path d="M7 21h10" /></svg>
                            <span class="text-sm font-semibold tracking-tight">Paper &amp; Pine</span>
                        </li>
                        <li class="flex items-center justify-center gap-2 text-slate-500">
                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="4" y="4" width="6" height="6" rx="1" /><rect x="14" y="4" width="6" height="6" rx="1" /><rect x="4" y="14" width="6" height="6" rx="1" /><rect x="14" y="14" width="6" height="6" rx="1" /></svg>
                            <span class="text-sm font-semibold tracking-tight">Loom Studio</span>
                        </li>
                        <li class="flex items-center justify-center gap-2 text-slate-500">
                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 8h11v7a4 4 0 0 1-4 4H9a4 4 0 0 1-4-4V8Z" /><path d="M16 9h2a2 2 0 0 1 0 4h-2M6 4v1M10 4v1M14 4v1" /></svg>
                            <span class="text-sm font-semibold tracking-tight">Brew &amp; Co.</span>
                        </li>
                        <li class="flex items-center justify-center gap-2 text-slate-500">
                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="8" /><path d="M4 12h16M12 4c2.5 2.4 2.5 13.6 0 16M12 4c-2.5 2.4-2.5 13.6 0 16" /></svg>
                            <span class="text-sm font-semibold tracking-tight">Atlas Supply</span>
                        </li>
                    </ul>
                </div>
            </section>

            <!-- Stats -->
            <section aria-label="Stoq in numbers" class="bg-white">
                <div class="mx-auto max-w-7xl px-5 py-14 sm:px-6 lg:px-8">
                    <dl class="grid grid-cols-2 gap-y-10 sm:grid-cols-4">
                        <div class="flex flex-col-reverse px-2 sm:border-l sm:border-slate-200 sm:px-6 sm:first:border-0 sm:first:pl-0">
                            <dt class="mt-1 text-sm font-medium text-slate-600">Shops on Stoq</dt>
                            <dd class="text-3xl font-semibold tracking-tight text-slate-900">2,000+</dd>
                        </div>
                        <div class="flex flex-col-reverse px-2 sm:border-l sm:border-slate-200 sm:px-6">
                            <dt class="mt-1 text-sm font-medium text-slate-600">Items tracked</dt>
                            <dd class="text-3xl font-semibold tracking-tight text-slate-900">4.2M</dd>
                        </div>
                        <div class="flex flex-col-reverse px-2 sm:border-l sm:border-slate-200 sm:px-6">
                            <dt class="mt-1 text-sm font-medium text-slate-600">Saved per week</dt>
                            <dd class="text-3xl font-semibold tracking-tight text-slate-900">11 hrs</dd>
                        </div>
                        <div class="flex flex-col-reverse px-2 sm:border-l sm:border-slate-200 sm:px-6">
                            <dt class="mt-1 text-sm font-medium text-slate-600">Uptime last year</dt>
                            <dd class="text-3xl font-semibold tracking-tight text-slate-900">99.9%</dd>
                        </div>
                    </dl>
                </div>
            </section>

            <!-- Features -->
            <section id="features" class="scroll-mt-24 bg-white pb-20 sm:pb-28">
                <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
                    <div class="mx-auto max-w-2xl text-center">
                        <p class="text-sm font-semibold uppercase tracking-[0.16em] text-emerald-700">Everything in one place</p>
                        <h2 class="mt-3 text-balance text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">The inventory toolkit your spreadsheet wishes it was.</h2>
                        <p class="mt-4 text-base leading-7 text-slate-600 sm:text-lg">No modules to bolt on, no consultants to call. Just the handful of tools a real shop needs to keep the right things on the shelf.</p>
                    </div>

                    <ul class="mt-14 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <li class="rounded-2xl border border-slate-200 bg-white p-6 transition-all duration-200 hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-lg hover:shadow-slate-900/5">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 7.5 12 2.25 3 7.5m18 0-9 5.25m9-5.25v9L12 21.75M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                            </div>
                            <h3 class="mt-5 text-base font-semibold text-slate-900">Real-time stock levels</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Every sale, return, and delivery updates your counts automatically. No more Friday-night stock takes.</p>
                        </li>
                        <li class="rounded-2xl border border-slate-200 bg-white p-6 transition-all duration-200 hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-lg hover:shadow-slate-900/5">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
                            </div>
                            <h3 class="mt-5 text-base font-semibold text-slate-900">Low-stock alerts</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Set a reorder point per product and get a nudge by email or in-app before you sell out.</p>
                        </li>
                        <li class="rounded-2xl border border-slate-200 bg-white p-6 transition-all duration-200 hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-lg hover:shadow-slate-900/5">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /><path d="m9 15 2 2 4-4" /></svg>
                            </div>
                            <h3 class="mt-5 text-base font-semibold text-slate-900">One-click purchase orders</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Turn low-stock suggestions into supplier-ready POs. No retyping SKUs into a spreadsheet.</p>
                        </li>
                        <li class="rounded-2xl border border-slate-200 bg-white p-6 transition-all duration-200 hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-lg hover:shadow-slate-900/5">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5v14M8 5v14M12 5v9M16 5v14M20 5v14" /><path d="M2.5 21h19" /></svg>
                            </div>
                            <h3 class="mt-5 text-base font-semibold text-slate-900">Barcode scanning</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Count a shelf in minutes using the phone in your pocket. Works with your existing labels.</p>
                        </li>
                        <li class="rounded-2xl border border-slate-200 bg-white p-6 transition-all duration-200 hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-lg hover:shadow-slate-900/5">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                            </div>
                            <h3 class="mt-5 text-base font-semibold text-slate-900">Every location, one view</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Shop floor, stockroom, market stall: see stock across every spot without juggling tabs.</p>
                        </li>
                        <li class="rounded-2xl border border-slate-200 bg-white p-6 transition-all duration-200 hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-lg hover:shadow-slate-900/5">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" /></svg>
                            </div>
                            <h3 class="mt-5 text-base font-semibold text-slate-900">Reports that make sense</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Spot best sellers, slow movers, and your real margin — without exporting a thing.</p>
                        </li>
                    </ul>
                </div>
            </section>

            <!-- How it works -->
            <section id="how-it-works" class="scroll-mt-24 border-y border-slate-200 bg-slate-50">
                <div class="mx-auto max-w-7xl px-5 py-20 sm:px-6 sm:py-28 lg:px-8">
                    <div class="mx-auto max-w-2xl text-center">
                        <p class="text-sm font-semibold uppercase tracking-[0.16em] text-emerald-700">How it works</p>
                        <h2 class="mt-3 text-balance text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">From messy spreadsheet to sorted in an afternoon.</h2>
                        <p class="mt-4 text-base leading-7 text-slate-600 sm:text-lg">No implementation project. No training week. Most shops are tracking stock before their coffee goes cold.</p>
                    </div>

                    <ol class="mt-14 grid gap-5 lg:grid-cols-3">
                        <li class="relative rounded-2xl border border-slate-200 bg-white p-7">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-700 text-sm font-semibold text-white">1</span>
                            <h3 class="mt-5 text-base font-semibold text-slate-900">Import your products</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Upload a CSV or scan your shelves. Stoq tidies up duplicates and gets your counts ready in minutes.</p>
                        </li>
                        <li class="relative rounded-2xl border border-slate-200 bg-white p-7">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-700 text-sm font-semibold text-white">2</span>
                            <h3 class="mt-5 text-base font-semibold text-slate-900">Set your reorder points</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Tell Stoq the minimum you want on hand for each product. We&rsquo;ll watch the numbers from there.</p>
                        </li>
                        <li class="relative rounded-2xl border border-slate-200 bg-white p-7">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-700 text-sm font-semibold text-white">3</span>
                            <h3 class="mt-5 text-base font-semibold text-slate-900">Sell, scan, and relax</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">As stock moves, Stoq keeps the count. Reorder only when it actually matters.</p>
                        </li>
                    </ol>

                    <p class="mt-10 text-center text-sm text-slate-600">Need a hand? We&rsquo;ll import your first product file for free. <a href="mailto:hello@stoq.app" class="font-semibold text-emerald-700 underline-offset-4 hover:underline">Talk to a human</a>.</p>
                </div>
            </section>

            <!-- Deep dive -->
            <section class="bg-white py-20 sm:py-28">
                <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
                    <div class="grid items-center gap-14 lg:grid-cols-2 lg:gap-20">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-emerald-700">Built for busy shop floors</p>
                            <h2 class="mt-3 text-balance text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Less counting. <br>More selling.</h2>
                            <p class="mt-4 text-base leading-7 text-slate-600 sm:text-lg">Stoq doesn&rsquo;t just store numbers — it tells you what to do next. Reorder suggestions land when they&rsquo;re useful, not after a stockout.</p>
                            <ul class="mt-8 space-y-4">
                                <li class="flex gap-3">
                                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>
                                    </span>
                                    <span class="text-sm leading-6 text-slate-700">A daily digest puts what needs attention first.</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>
                                    </span>
                                    <span class="text-sm leading-6 text-slate-700">Supplier, cost, and quantities are pre-filled for you.</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>
                                    </span>
                                    <span class="text-sm leading-6 text-slate-700">Every stock movement is logged, so nothing gets lost.</span>
                                </li>
                            </ul>
                            <a href="#features" class="mt-8 inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700 transition-colors hover:text-emerald-800">
                                Explore all features
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                            </a>
                        </div>

                        <div class="relative" aria-hidden="true">
                            <div class="absolute -inset-5 -z-10 rounded-[2.5rem] bg-emerald-600/10 blur-2xl"></div>
                            <div class="rounded-3xl bg-slate-50 p-4 ring-1 ring-slate-200 sm:p-6">
                                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
                                    <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                                        <p class="text-[12px] font-semibold text-slate-900">Reorder suggestions</p>
                                        <span class="rounded-full bg-amber-50 px-2 py-0.5 text-[9px] font-semibold text-amber-700 ring-1 ring-amber-200">3 items</span>
                                    </div>
                                    <div class="divide-y divide-slate-100">
                                        <div class="flex items-center gap-3 px-4 py-3">
                                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-[10px] font-semibold text-emerald-700 ring-1 ring-emerald-100">LT</span>
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-[11px] font-medium text-slate-900">Linen Tote Bag</p>
                                                <p class="text-[9px] text-slate-500">Northwind Goods</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-[11px] font-semibold text-slate-900">12 units</p>
                                                <p class="text-[9px] text-slate-500">$96.00</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 px-4 py-3">
                                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-[10px] font-semibold text-emerald-700 ring-1 ring-emerald-100">CM</span>
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-[11px] font-medium text-slate-900">Ceramic Mug 350ml</p>
                                                <p class="text-[9px] text-slate-500">Clay &amp; Co.</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-[11px] font-semibold text-slate-900">24 units</p>
                                                <p class="text-[9px] text-slate-500">$84.00</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 px-4 py-3">
                                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-[10px] font-semibold text-emerald-700 ring-1 ring-emerald-100">OC</span>
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-[11px] font-medium text-slate-900">Organic Coffee 1kg</p>
                                                <p class="text-[9px] text-slate-500">Roast Works</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-[11px] font-semibold text-slate-900">10 units</p>
                                                <p class="text-[9px] text-slate-500">$142.00</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-t border-slate-100 p-3">
                                        <span class="flex items-center justify-center gap-1.5 rounded-lg bg-emerald-700 px-3 py-2 text-[11px] font-semibold text-white">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                            Create purchase order
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-3 rounded-xl bg-white p-3 shadow-lg ring-1 ring-slate-200 sm:absolute sm:-bottom-7 sm:right-6 sm:mt-0 sm:w-[260px]">
                                    <div class="flex items-center gap-2.5">
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m4.5 12.75 6 6 9-13.5" /></svg>
                                        </span>
                                        <div class="min-w-0">
                                            <p class="text-[10px] font-semibold text-slate-900">PO-1042 created</p>
                                            <p class="truncate text-[9px] text-slate-500">$322.00 · sent to 3 suppliers</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testimonials -->
            <section aria-labelledby="testimonials-heading" class="border-y border-slate-200 bg-slate-50">
                <div class="mx-auto max-w-7xl px-5 py-20 sm:px-6 sm:py-28 lg:px-8">
                    <div class="mx-auto max-w-2xl text-center">
                        <p class="text-sm font-semibold uppercase tracking-[0.16em] text-emerald-700">Loved by shopkeepers</p>
                        <h2 id="testimonials-heading" class="mt-3 text-balance text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Shops that stopped guessing.</h2>
                        <div class="mt-4 flex items-center justify-center gap-2 text-sm text-slate-600">
                            <span class="flex gap-0.5 text-amber-400" role="img" aria-label="Rated 4.9 out of 5">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                            </span>
                            4.9 out of 5 from 380+ reviews
                        </div>
                    </div>

                    <div class="mt-14 grid gap-5 lg:grid-cols-3">
                        <figure class="flex flex-col rounded-2xl border border-slate-200 bg-white p-6">
                            <div class="flex gap-0.5 text-amber-400" role="img" aria-label="Rated 5 out of 5">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                            </div>
                            <blockquote class="mt-4 flex-1 text-sm leading-6 text-slate-700">&ldquo;We used to guess what to reorder. Now Stoq tells us — and we haven&rsquo;t had a stockout on our top ten lines in six months.&rdquo;</blockquote>
                            <figcaption class="mt-6 flex items-center gap-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-100 text-xs font-semibold text-emerald-800" aria-hidden="true">MC</span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">Maya Chen</p>
                                    <p class="text-xs text-slate-600">Owner, Loom Studio · Portland, OR</p>
                                </div>
                            </figcaption>
                        </figure>

                        <figure class="flex flex-col rounded-2xl border border-slate-200 bg-white p-6">
                            <div class="flex gap-0.5 text-amber-400" role="img" aria-label="Rated 5 out of 5">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                            </div>
                            <blockquote class="mt-4 flex-1 text-sm leading-6 text-slate-700">&ldquo;Stock takes went from a full Sunday to about twenty minutes with the barcode scanner. That alone paid for the year.&rdquo;</blockquote>
                            <figcaption class="mt-6 flex items-center gap-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-100 text-xs font-semibold text-emerald-800" aria-hidden="true">DO</span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">Daniel Okafor</p>
                                    <p class="text-xs text-slate-600">Co-owner, Brew &amp; Co. · Austin, TX</p>
                                </div>
                            </figcaption>
                        </figure>

                        <figure class="flex flex-col rounded-2xl border border-slate-200 bg-white p-6">
                            <div class="flex gap-0.5 text-amber-400" role="img" aria-label="Rated 5 out of 5">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.385a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                            </div>
                            <blockquote class="mt-4 flex-1 text-sm leading-6 text-slate-700">&ldquo;I run two shops and a weekend market stall. Seeing all three in one place is the whole reason I switched.&rdquo;</blockquote>
                            <figcaption class="mt-6 flex items-center gap-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-100 text-xs font-semibold text-emerald-800" aria-hidden="true">SR</span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">Sofia Ruiz</p>
                                    <p class="text-xs text-slate-600">Founder, Atlas Supply · Chicago, IL</p>
                                </div>
                            </figcaption>
                        </figure>
                    </div>
                </div>
            </section>

            <!-- Pricing -->
            <section id="pricing" class="scroll-mt-24 bg-white py-20 sm:py-28">
                <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
                    <div class="mx-auto max-w-2xl text-center">
                        <p class="text-sm font-semibold uppercase tracking-[0.16em] text-emerald-700">Simple pricing</p>
                        <h2 class="mt-3 text-balance text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Priced for real shops, not enterprise budgets.</h2>
                        <p class="mt-4 text-base leading-7 text-slate-600 sm:text-lg">Start free for 14 days. Pick a plan when you&rsquo;re ready — change or cancel whenever you like.</p>
                    </div>

                    <div x-data="{ annual: true }">
                        <p class="sr-only" aria-live="polite" x-text="annual ? 'Showing annual pricing with 20 percent savings' : 'Showing monthly pricing'"></p>

                        <div class="mt-8 flex justify-center">
                            <div class="inline-flex items-center rounded-full bg-slate-100 p-1 ring-1 ring-slate-200" role="group" aria-label="Billing period">
                                <button type="button" @click="annual = false" :aria-pressed="!annual" :class="!annual ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900'" class="rounded-full px-4 py-2 text-sm font-semibold transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600">
                                    Monthly
                                </button>
                                <button type="button" @click="annual = true" :aria-pressed="annual" :class="annual ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900'" class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600">
                                    Annual
                                    <span class="ml-1.5 rounded-full bg-emerald-100 px-1.5 py-0.5 text-[10px] font-bold text-emerald-800">Save 20%</span>
                                </button>
                            </div>
                        </div>

                        <div class="mt-12 grid gap-6 lg:grid-cols-3">
                            <!-- Starter -->
                            <div class="flex flex-col rounded-2xl border border-slate-200 bg-white p-7">
                                <h3 class="text-base font-semibold text-slate-900">Starter</h3>
                                <p class="mt-1.5 text-sm leading-6 text-slate-600">For a single shop finding its feet.</p>
                                <p class="mt-6 flex items-baseline gap-1">
                                    <span class="text-4xl font-semibold tracking-tight text-slate-900">$<span x-text="annual ? '15' : '19'">15</span></span>
                                    <span class="text-sm font-medium text-slate-500">/ month</span>
                                </p>
                                <p class="mt-1 min-h-[18px] text-xs text-slate-500" x-text="annual ? 'Billed $180 once a year' : 'Billed monthly'">Billed $180 once a year</p>
                                <a href="{{ route('register') }}" class="mt-6 inline-flex items-center justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-slate-800 shadow-sm ring-1 ring-slate-300 transition-colors hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2">Start free trial</a>
                                <ul class="mt-7 space-y-3 text-sm text-slate-700">
                                    <li class="flex gap-2.5"><svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>1 location</li>
                                    <li class="flex gap-2.5"><svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>Up to 500 items</li>
                                    <li class="flex gap-2.5"><svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>Real-time stock levels</li>
                                    <li class="flex gap-2.5"><svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>Low-stock alerts</li>
                                    <li class="flex gap-2.5"><svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>Barcode scanning</li>
                                    <li class="flex gap-2.5"><svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>Email support</li>
                                </ul>
                            </div>

                            <!-- Growth -->
                            <div class="relative flex flex-col rounded-2xl border-2 border-emerald-600 bg-white p-7 shadow-xl shadow-emerald-900/5">
                                <span class="absolute -top-3.5 left-7 rounded-full bg-emerald-700 px-3 py-1 text-[11px] font-semibold text-white">Most popular</span>
                                <h3 class="text-base font-semibold text-slate-900">Growth</h3>
                                <p class="mt-1.5 text-sm leading-6 text-slate-600">For busy shops ready to automate reordering.</p>
                                <p class="mt-6 flex items-baseline gap-1">
                                    <span class="text-4xl font-semibold tracking-tight text-slate-900">$<span x-text="annual ? '39' : '49'">39</span></span>
                                    <span class="text-sm font-medium text-slate-500">/ month</span>
                                </p>
                                <p class="mt-1 min-h-[18px] text-xs text-slate-500" x-text="annual ? 'Billed $468 once a year' : 'Billed monthly'">Billed $468 once a year</p>
                                <a href="{{ route('register') }}" class="mt-6 inline-flex items-center justify-center rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-emerald-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2">Start free trial</a>
                                <ul class="mt-7 space-y-3 text-sm text-slate-700">
                                    <li class="flex gap-2.5"><svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>Everything in Starter</li>
                                    <li class="flex gap-2.5"><svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>Up to 3 locations</li>
                                    <li class="flex gap-2.5"><svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>Unlimited items</li>
                                    <li class="flex gap-2.5"><svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>One-click purchase orders</li>
                                    <li class="flex gap-2.5"><svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>Supplier management</li>
                                    <li class="flex gap-2.5"><svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>Sales &amp; margin reports</li>
                                    <li class="flex gap-2.5"><svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>Priority support</li>
                                </ul>
                            </div>

                            <!-- Pro -->
                            <div class="flex flex-col rounded-2xl border border-slate-200 bg-white p-7">
                                <h3 class="text-base font-semibold text-slate-900">Pro</h3>
                                <p class="mt-1.5 text-sm leading-6 text-slate-600">For multi-location operators and small teams.</p>
                                <p class="mt-6 flex items-baseline gap-1">
                                    <span class="text-4xl font-semibold tracking-tight text-slate-900">$<span x-text="annual ? '79' : '99'">79</span></span>
                                    <span class="text-sm font-medium text-slate-500">/ month</span>
                                </p>
                                <p class="mt-1 min-h-[18px] text-xs text-slate-500" x-text="annual ? 'Billed $948 once a year' : 'Billed monthly'">Billed $948 once a year</p>
                                <a href="mailto:hello@stoq.app?subject=Stoq%20Pro" class="mt-6 inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-slate-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2">Talk to sales</a>
                                <ul class="mt-7 space-y-3 text-sm text-slate-700">
                                    <li class="flex gap-2.5"><svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>Everything in Growth</li>
                                    <li class="flex gap-2.5"><svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>Unlimited locations</li>
                                    <li class="flex gap-2.5"><svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>Team roles &amp; permissions</li>
                                    <li class="flex gap-2.5"><svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>API &amp; webhooks</li>
                                    <li class="flex gap-2.5"><svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>Custom report exports</li>
                                    <li class="flex gap-2.5"><svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>Dedicated onboarding</li>
                                    <li class="flex gap-2.5"><svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m4.5 12.75 6 6 9-13.5" /></svg>Priority support</li>
                                </ul>
                            </div>
                        </div>

                        <p class="mt-8 text-center text-xs leading-6 text-slate-500">Prices in USD per month. Annual plans are billed once per year. Every plan starts with a 14-day free trial — no credit card required.</p>
                    </div>
                </div>
            </section>

            <!-- FAQ -->
            <section id="faq" class="scroll-mt-24 border-y border-slate-200 bg-slate-50">
                <div class="mx-auto max-w-7xl px-5 py-20 sm:px-6 sm:py-28 lg:px-8">
                    <div class="grid gap-12 lg:grid-cols-[0.85fr_1.15fr] lg:gap-16">
                        <div class="lg:sticky lg:top-24 lg:self-start">
                            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-emerald-700">FAQ</p>
                            <h2 class="mt-3 text-balance text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Questions, answered.</h2>
                            <p class="mt-4 text-base leading-7 text-slate-600">Everything you might want to know before your first stock count. Can&rsquo;t find it? We&rsquo;re a short email away.</p>

                            <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-5">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" /></svg>
                                    </span>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">Still stuck?</p>
                                        <p class="text-xs text-slate-600">A real person replies, usually within a day.</p>
                                    </div>
                                </div>
                                <a href="mailto:hello@stoq.app" class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-slate-800 shadow-sm ring-1 ring-slate-300 transition-colors hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2">
                                    Email hello@stoq.app
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                                </a>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <details class="group rounded-xl border border-slate-200 bg-white px-5 [&_summary::-webkit-details-marker]:hidden">
                                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 rounded-lg py-4 text-sm font-semibold text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2">
                                    Do I need to buy a barcode scanner?
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition-transform duration-200 group-open:rotate-45">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    </span>
                                </summary>
                                <p class="pb-4 pr-8 text-sm leading-6 text-slate-600">No. Stoq&rsquo;s scanner uses your phone&rsquo;s camera. If you already own a Bluetooth scanner, it works too.</p>
                            </details>

                            <details class="group rounded-xl border border-slate-200 bg-white px-5 [&_summary::-webkit-details-marker]:hidden">
                                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 rounded-lg py-4 text-sm font-semibold text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2">
                                    Can I import my existing product list?
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition-transform duration-200 group-open:rotate-45">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    </span>
                                </summary>
                                <p class="pb-4 pr-8 text-sm leading-6 text-slate-600">Yes. Upload a CSV from Excel, Google Sheets, or your current system, and we&rsquo;ll map the columns for you. Duplicates get flagged before anything is saved.</p>
                            </details>

                            <details class="group rounded-xl border border-slate-200 bg-white px-5 [&_summary::-webkit-details-marker]:hidden">
                                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 rounded-lg py-4 text-sm font-semibold text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2">
                                    Does Stoq work with my online store?
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition-transform duration-200 group-open:rotate-45">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    </span>
                                </summary>
                                <p class="pb-4 pr-8 text-sm leading-6 text-slate-600">Stoq connects with Shopify and WooCommerce, so online sales update your counts automatically. More integrations are on the way.</p>
                            </details>

                            <details class="group rounded-xl border border-slate-200 bg-white px-5 [&_summary::-webkit-details-marker]:hidden">
                                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 rounded-lg py-4 text-sm font-semibold text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2">
                                    How long does setup actually take?
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition-transform duration-200 group-open:rotate-45">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    </span>
                                </summary>
                                <p class="pb-4 pr-8 text-sm leading-6 text-slate-600">Most shops are up and running in under ten minutes. If you&rsquo;d like a hand, our team will import your first file for free.</p>
                            </details>

                            <details class="group rounded-xl border border-slate-200 bg-white px-5 [&_summary::-webkit-details-marker]:hidden">
                                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 rounded-lg py-4 text-sm font-semibold text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2">
                                    Can my team use it too?
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition-transform duration-200 group-open:rotate-45">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    </span>
                                </summary>
                                <p class="pb-4 pr-8 text-sm leading-6 text-slate-600">Growth and Pro plans include multiple seats. Pro adds role-based permissions, so staff only see the locations and actions they need.</p>
                            </details>

                            <details class="group rounded-xl border border-slate-200 bg-white px-5 [&_summary::-webkit-details-marker]:hidden">
                                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 rounded-lg py-4 text-sm font-semibold text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2">
                                    What happens when my trial ends?
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition-transform duration-200 group-open:rotate-45">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    </span>
                                </summary>
                                <p class="pb-4 pr-8 text-sm leading-6 text-slate-600">You&rsquo;ll be asked to pick a plan. If you don&rsquo;t, your account pauses — nothing is deleted, and you can export your data at any time.</p>
                            </details>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Final CTA -->
            <section class="bg-white py-20 sm:py-28">
                <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
                    <div class="relative overflow-hidden rounded-3xl bg-slate-900 px-6 py-16 sm:px-12 sm:py-20 lg:px-16">
                        <div class="pointer-events-none absolute -right-24 -top-24 h-80 w-80 rounded-full bg-emerald-500/20 blur-3xl" aria-hidden="true"></div>
                        <div class="pointer-events-none absolute -bottom-32 -left-24 h-80 w-80 rounded-full bg-emerald-400/10 blur-3xl" aria-hidden="true"></div>
                        <div class="pointer-events-none absolute inset-0 opacity-[0.07]" style="background-image: linear-gradient(to right, #fff 1px, transparent 1px), linear-gradient(to bottom, #fff 1px, transparent 1px); background-size: 48px 48px; -webkit-mask-image: radial-gradient(ellipse 70% 70% at 50% 50%, black, transparent); mask-image: radial-gradient(ellipse 70% 70% at 50% 50%, black, transparent);" aria-hidden="true"></div>

                        <div class="relative mx-auto max-w-2xl text-center">
                            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-emerald-300">Ready when you are</p>
                            <h2 class="mt-3 text-balance text-3xl font-semibold tracking-tight text-white sm:text-4xl">Stop counting. <br>Start selling.</h2>
                            <p class="mt-4 text-base leading-7 text-slate-300 sm:text-lg">Join 2,000+ shops that know exactly what&rsquo;s on the shelf — and what to order next.</p>
                            <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                                <a href="{{ route('register') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-white px-6 py-3.5 text-sm font-semibold text-slate-900 shadow-sm transition-colors hover:bg-slate-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-slate-900 sm:w-auto">
                                    Start your free trial
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                                </a>
                                <a href="mailto:hello@stoq.app" class="inline-flex w-full items-center justify-center rounded-xl px-6 py-3.5 text-sm font-semibold text-white ring-1 ring-white/25 transition-colors hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-slate-900 sm:w-auto">
                                    Talk to sales
                                </a>
                            </div>
                            <p class="mt-6 flex flex-wrap items-center justify-center gap-x-4 gap-y-1 text-xs font-medium text-slate-300">
                                <span>14-day free trial</span>
                                <span class="text-slate-600" aria-hidden="true">·</span>
                                <span>No credit card</span>
                                <span class="text-slate-600" aria-hidden="true">·</span>
                                <span>Cancel anytime</span>
                            </p>
                        </div>
                    </div>
                </div>
            </section>

        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-5 py-14 sm:px-6 lg:px-8">
                <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-6">
                    <div class="lg:col-span-2">
                        <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2" aria-label="Stoq home">
                            <svg viewBox="0 0 32 32" class="h-8 w-8" fill="none" aria-hidden="true">
                                <rect width="32" height="32" rx="9" fill="#047857" />
                                <path d="M9 12.25 16 8.5l7 3.75v7.5L16 23.5l-7-3.75v-7.5Z" stroke="#fff" stroke-width="1.7" stroke-linejoin="round" />
                                <path d="m9 12.25 7 3.75 7-3.75M16 16v7.5" stroke="#fff" stroke-width="1.7" stroke-linejoin="round" />
                            </svg>
                            <span class="text-[1.35rem] font-semibold tracking-tight text-slate-900">stoq</span>
                        </a>
                        <p class="mt-4 max-w-xs text-sm leading-6 text-slate-600">Inventory that keeps up with your shop. Built for independent businesses, not enterprise committees.</p>
                        <p class="mt-5 inline-flex items-center gap-2 rounded-full bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-700 ring-1 ring-slate-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500" aria-hidden="true"></span>
                            All systems operational
                        </p>
                    </div>

                    <div>
                        <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-900">Product</h2>
                        <ul class="mt-4 space-y-3">
                            <li><a href="#features" class="text-sm text-slate-600 transition-colors hover:text-slate-900">Features</a></li>
                            <li><a href="#how-it-works" class="text-sm text-slate-600 transition-colors hover:text-slate-900">How it works</a></li>
                            <li><a href="#pricing" class="text-sm text-slate-600 transition-colors hover:text-slate-900">Pricing</a></li>
                            <li><a href="#faq" class="text-sm text-slate-600 transition-colors hover:text-slate-900">FAQ</a></li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-900">Company</h2>
                        <ul class="mt-4 space-y-3">
                            <li><a href="#" class="text-sm text-slate-600 transition-colors hover:text-slate-900">About</a></li>
                            <li><a href="#" class="text-sm text-slate-600 transition-colors hover:text-slate-900">Blog</a></li>
                            <li><a href="#" class="text-sm text-slate-600 transition-colors hover:text-slate-900">Careers</a></li>
                            <li><a href="mailto:hello@stoq.app" class="text-sm text-slate-600 transition-colors hover:text-slate-900">Contact</a></li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-900">Resources</h2>
                        <ul class="mt-4 space-y-3">
                            <li><a href="#" class="text-sm text-slate-600 transition-colors hover:text-slate-900">Help center</a></li>
                            <li><a href="#" class="text-sm text-slate-600 transition-colors hover:text-slate-900">Barcode guide</a></li>
                            <li><a href="#" class="text-sm text-slate-600 transition-colors hover:text-slate-900">Inventory templates</a></li>
                            <li><a href="#" class="text-sm text-slate-600 transition-colors hover:text-slate-900">API docs</a></li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-900">Legal</h2>
                        <ul class="mt-4 space-y-3">
                            <li><a href="#" class="text-sm text-slate-600 transition-colors hover:text-slate-900">Privacy</a></li>
                            <li><a href="#" class="text-sm text-slate-600 transition-colors hover:text-slate-900">Terms</a></li>
                            <li><a href="#" class="text-sm text-slate-600 transition-colors hover:text-slate-900">Security</a></li>
                            <li><a href="#" class="text-sm text-slate-600 transition-colors hover:text-slate-900">Status</a></li>
                        </ul>
                    </div>
                </div>

                <div class="mt-12 flex flex-col gap-4 border-t border-slate-200 pt-8 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs text-slate-600">&copy; {{ date('Y') }} Stoq. All rights reserved.</p>
                    <div class="flex items-center gap-5 text-xs font-medium text-slate-600">
                        <a href="#" class="transition-colors hover:text-slate-900">X</a>
                        <a href="#" class="transition-colors hover:text-slate-900">LinkedIn</a>
                        <a href="#" class="transition-colors hover:text-slate-900">Instagram</a>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
