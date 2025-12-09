<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>dPanel - Next-Gen SAP Procurement Platform | Enterprise Solution</title>
    <meta name="description" content="Transform your procurement process with dPanel - A modern, intelligent platform for RFQs, supplier management, and order processing. Built for enterprises ready to modernize.">
    <meta name="keywords" content="SAP, procurement, RFQ, supplier management, enterprise, dPanel">

    <!-- Preconnect for critical domains -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="dns-prefetch" href="https://fonts.bunny.net">

    <!-- CSS -->
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @tallStackUiScript

    <!-- Inline Critical CSS -->
    <style>
        /* Critical CSS for above-the-fold content */
        :root {
            --animate-float: float 6s ease-in-out infinite;
            --animate-glow: glow 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @keyframes glow {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        .animate-float { animation: var(--animate-float); }
        .animate-glow { animation: var(--animate-glow); }

        /* Optimize transitions */
        *, *::before, *::after {
            -webkit-tap-highlight-color: transparent;
        }

        /* Improve scrolling performance */
        .bg-fixed-background {
            transform: translateZ(0);
            backface-visibility: hidden;
            perspective: 1000;
        }
    </style>
</head>
<body class="antialiased bg-black text-slate-100" x-data="{ mobileMenuOpen: false }">
<!-- Navigation -->
<nav class="fixed w-full top-0 z-50 bg-black/80 backdrop-blur-2xl border-b border-white/10 supports-backdrop-blur:bg-black/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <a href="/" class="flex items-center focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg">
                <div class="relative group">
                    <div class="absolute -inset-2 bg-gradient-to-r from-cyan-500 via-blue-500 to-purple-500 rounded-2xl blur-lg opacity-30 group-hover:opacity-50 transition-opacity duration-500"></div>
                    <div class="relative px-6 py-3 bg-gradient-to-r from-cyan-500/10 via-blue-500/10 to-purple-500/10 backdrop-blur-xl border border-white/10 rounded-xl">
                        <img
                            src="{{ asset('/assets/images/JVD.png') }}"
                            class="h-8 w-auto"
                            alt="dPanel"
                            width="120"
                            height="32"
                            loading="eager"
                        />
                    </div>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-sm font-medium text-slate-300 hover:text-white transition-colors relative group focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-2 py-1">
                    {{ __('Features') }}
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-cyan-500 to-blue-500 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="#how-it-works" class="text-sm font-medium text-slate-300 hover:text-white transition-colors relative group focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-2 py-1">
                    {{ __('How It Works') }}
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-blue-500 to-purple-500 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="#pricing" class="text-sm font-medium text-slate-300 hover:text-white transition-colors relative group focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-2 py-1">
                    {{ __('Pricing') }}
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-purple-500 to-pink-500 group-hover:w-full transition-all duration-300"></span>
                </a>
            </div>

            <!-- Auth Buttons -->
            <div class="hidden md:flex items-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-slate-300 hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-2 py-1">
                        {{ __('Dashboard') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-semibold text-white hover:text-cyan-400 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg">
                        {{ __('Sign in') }}
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="group relative inline-flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-white overflow-hidden rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <div class="absolute inset-0 bg-gradient-to-r from-cyan-500 via-blue-500 to-purple-500"></div>
                            <div class="absolute inset-0 bg-gradient-to-r from-cyan-400 via-blue-400 to-purple-400 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <span class="relative">{{ __('Get Started') }}</span>
                            <svg class="relative w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    @endif
                @endauth
            </div>

            <!-- Mobile menu button -->
            <button
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="md:hidden p-2 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500"
                :aria-expanded="mobileMenuOpen"
                aria-label="Toggle menu"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div
        x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden border-t border-white/10 bg-black/95 backdrop-blur-2xl supports-backdrop-blur:bg-black/95"
        style="display: none;"
    >
        <div class="px-4 py-6 space-y-4">
            <a href="#features" class="block py-2 text-base font-medium text-slate-300 hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-2">{{ __('Features') }}</a>
            <a href="#how-it-works" class="block py-2 text-base font-medium text-slate-300 hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-2">{{ __('How It Works') }}</a>
            <a href="#pricing" class="block py-2 text-base font-medium text-slate-300 hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-2">{{ __('Pricing') }}</a>
            @guest
                <div class="pt-4 space-y-3 border-t border-white/10">
                    <a href="{{ route('login') }}" class="block py-2.5 text-center text-base font-medium text-slate-300 hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 rounded">{{ __('Sign in') }}</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="block py-3 text-center text-base font-bold text-white bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl hover:from-cyan-600 hover:to-blue-600 transition-all focus:outline-none focus:ring-2 focus:ring-blue-500">
                            {{ __('Get Started') }}
                        </a>
                    @endif
                </div>
            @endguest
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="relative pt-32 pb-20 md:pt-40 md:pb-32 overflow-hidden">
    <!-- Background Gradients -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-slate-950 dark:via-blue-950/20 dark:to-indigo-950/20 bg-fixed-background"></div>
    <div class="absolute top-0 right-0 w-1/2 h-1/2 bg-gradient-to-br from-blue-400/20 to-indigo-400/20 dark:from-blue-600/10 dark:to-indigo-600/10 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-1/2 h-1/2 bg-gradient-to-tr from-purple-400/20 to-pink-400/20 dark:from-purple-600/10 dark:to-pink-600/10 blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-4xl mx-auto">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-2 mb-8 bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm rounded-full shadow-lg shadow-blue-500/10 border border-blue-200/50 dark:border-blue-500/30">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    </span>
                <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Next-Gen SAP Procurement Platform') }}</span>
            </div>

            <!-- Main Heading -->
            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black tracking-tight mb-6">
                    <span class="block bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 dark:from-white dark:via-blue-200 dark:to-indigo-200 bg-clip-text text-transparent">
                        {{ __('Transform Your') }}
                    </span>
                <span class="block bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        {{ __('Procurement Process') }}
                    </span>
            </h1>

            <!-- Subtitle -->
            <p class="text-lg md:text-xl text-slate-600 dark:text-slate-400 mb-10 max-w-3xl mx-auto leading-relaxed">
                {{ __('dPanel is a modern, intelligent procurement platform that streamlines RFQs, supplier management, and order processing. Built for enterprises ready to modernize their SAP procurement workflow.') }}
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="group relative inline-flex items-center gap-2 px-8 py-4 text-base font-bold text-white bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 rounded-xl shadow-2xl shadow-blue-500/40 hover:shadow-blue-500/60 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50">
                    {{ __('Start Free Trial') }}
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
                <a href="#features" class="inline-flex items-center gap-2 px-8 py-4 text-base font-semibold text-slate-900 dark:text-white bg-white dark:bg-slate-900 rounded-xl shadow-xl hover:shadow-2xl border border-slate-200 dark:border-slate-800 hover:border-blue-300 dark:hover:border-blue-700 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50">
                    {{ __('Learn More') }}
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </a>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 mt-16 pt-16 border-t border-slate-200 dark:border-slate-800">
                <div>
                    <div class="text-3xl md:text-4xl font-black bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent mb-2">500+</div>
                    <div class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Active Users') }}</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-black bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">98%</div>
                    <div class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Satisfaction') }}</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-black bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-2">50M+</div>
                    <div class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Orders Processed') }}</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-black bg-gradient-to-r from-pink-600 to-red-600 bg-clip-text text-transparent mb-2">24/7</div>
                    <div class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Support') }}</div>
                </div>
            </div>

            <!-- Trust Indicators -->
            <div class="mt-12 pt-8">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 text-center mb-6">{{ __('Trusted by leading enterprises worldwide') }}</p>
                <div class="flex flex-wrap items-center justify-center gap-8 opacity-50 grayscale hover:opacity-75 hover:grayscale-0 transition-all duration-300" role="list" aria-label="Trusted companies">
                    <div class="text-xl font-bold text-slate-600 dark:text-slate-400" role="listitem">SAP</div>
                    <div class="text-xl font-bold text-slate-600 dark:text-slate-400" role="listitem">Ariba</div>
                    <div class="text-xl font-bold text-slate-600 dark:text-slate-400" role="listitem">Oracle</div>
                    <div class="text-xl font-bold text-slate-600 dark:text-slate-400" role="listitem">IBM</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-24 md:py-32 bg-gradient-to-b from-black via-slate-950 to-black relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 50% 50%, rgba(6, 182, 212, 0.05) 1px, transparent 1px); background-size: 50px 50px;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
            <div class="inline-flex items-center gap-2 px-4 py-2 mb-6 bg-cyan-500/10 border border-cyan-500/20 rounded-full">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span class="text-sm font-bold text-cyan-400">{{ __('POWERFUL FEATURES') }}</span>
            </div>
            <h2 class="text-4xl md:text-6xl font-black mb-6">
                    <span class="bg-gradient-to-r from-white via-slate-200 to-white bg-clip-text text-transparent">
                        {{ __('Everything You Need') }}
                    </span>
            </h2>
            <p class="text-xl text-slate-400 max-w-3xl mx-auto">
                {{ __('Cutting-edge features designed for modern procurement teams') }}
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Feature 1 -->
            <div class="group relative p-8 bg-gradient-to-br from-cyan-950/30 to-blue-950/30 backdrop-blur-xl rounded-2xl border border-cyan-500/20 hover:border-cyan-400/40 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-cyan-500/10 focus-within:scale-105 focus-within:shadow-2xl focus-within:shadow-cyan-500/10">
                <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/0 to-blue-500/0 group-hover:from-cyan-500/5 group-hover:to-blue-500/5 rounded-2xl transition-all duration-300"></div>
                <div class="relative">
                    <div class="w-14 h-14 mb-6 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-cyan-500/30 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-white">{{ __('AI-Powered RFQ') }}</h3>
                    <p class="text-slate-400">{{ __('Intelligent RFQ creation and management with automated supplier matching and real-time collaboration.') }}</p>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="group relative p-8 bg-gradient-to-br from-blue-950/30 to-indigo-950/30 backdrop-blur-xl rounded-2xl border border-blue-500/20 hover:border-blue-400/40 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-blue-500/10 focus-within:scale-105 focus-within:shadow-2xl focus-within:shadow-blue-500/10">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/0 to-indigo-500/0 group-hover:from-blue-500/5 group-hover:to-indigo-500/5 rounded-2xl transition-all duration-300"></div>
                <div class="relative">
                    <div class="w-14 h-14 mb-6 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-white">{{ __('Smart Supplier Network') }}</h3>
                    <p class="text-slate-400">{{ __('Connect with verified suppliers globally. AI-powered recommendations based on your needs.') }}</p>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="group relative p-8 bg-gradient-to-br from-indigo-950/30 to-purple-950/30 backdrop-blur-xl rounded-2xl border border-indigo-500/20 hover:border-indigo-400/40 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-indigo-500/10 focus-within:scale-105 focus-within:shadow-2xl focus-within:shadow-indigo-500/10">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/0 to-purple-500/0 group-hover:from-indigo-500/5 group-hover:to-purple-500/5 rounded-2xl transition-all duration-300"></div>
                <div class="relative">
                    <div class="w-14 h-14 mb-6 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-white">{{ __('Predictive Analytics') }}</h3>
                    <p class="text-slate-400">{{ __('Real-time insights and predictive analytics to optimize spending and supplier performance.') }}</p>
                </div>
            </div>

            <!-- Feature 4 -->
            <div class="group relative p-8 bg-gradient-to-br from-purple-950/30 to-pink-950/30 backdrop-blur-xl rounded-2xl border border-purple-500/20 hover:border-purple-400/40 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-purple-500/10 focus-within:scale-105 focus-within:shadow-2xl focus-within:shadow-purple-500/10">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/0 to-pink-500/0 group-hover:from-purple-500/5 group-hover:to-pink-500/5 rounded-2xl transition-all duration-300"></div>
                <div class="relative">
                    <div class="w-14 h-14 mb-6 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/30 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-white">{{ __('Enterprise Security') }}</h3>
                    <p class="text-slate-400">{{ __('Bank-level encryption, SOC 2 compliant, with granular role-based access control.') }}</p>
                </div>
            </div>

            <!-- Feature 5 -->
            <div class="group relative p-8 bg-gradient-to-br from-pink-950/30 to-rose-950/30 backdrop-blur-xl rounded-2xl border border-pink-500/20 hover:border-pink-400/40 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-pink-500/10 focus-within:scale-105 focus-within:shadow-2xl focus-within:shadow-pink-500/10">
                <div class="absolute inset-0 bg-gradient-to-br from-pink-500/0 to-rose-500/0 group-hover:from-pink-500/5 group-hover:to-rose-500/5 rounded-2xl transition-all duration-300"></div>
                <div class="relative">
                    <div class="w-14 h-14 mb-6 bg-gradient-to-br from-pink-500 to-rose-600 rounded-xl flex items-center justify-center shadow-lg shadow-pink-500/30 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-white">{{ __('Lightning Fast') }}</h3>
                    <p class="text-slate-400">{{ __('Process thousands of RFQs simultaneously with our distributed cloud infrastructure.') }}</p>
                </div>
            </div>

            <!-- Feature 6 -->
            <div class="group relative p-8 bg-gradient-to-br from-emerald-950/30 to-green-950/30 backdrop-blur-xl rounded-2xl border border-emerald-500/20 hover:border-emerald-400/40 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-emerald-500/10 focus-within:scale-105 focus-within:shadow-2xl focus-within:shadow-emerald-500/10">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/0 to-green-500/0 group-hover:from-emerald-500/5 group-hover:to-green-500/5 rounded-2xl transition-all duration-300"></div>
                <div class="relative">
                    <div class="w-14 h-14 mb-6 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-white">{{ __('Global & Multi-Currency') }}</h3>
                    <p class="text-slate-400">{{ __('Support for 50+ languages and 150+ currencies with automatic conversion.') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="py-24 md:py-32 bg-black relative overflow-hidden">
    <div class="absolute inset-0" style="background-image: linear-gradient(rgba(139, 92, 246, 0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(139, 92, 246, 0.03) 1px, transparent 1px); background-size: 50px 50px;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
            <div class="inline-flex items-center gap-2 px-4 py-2 mb-6 bg-purple-500/10 border border-purple-500/20 rounded-full">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span class="text-sm font-bold text-purple-400">{{ __('HOW IT WORKS') }}</span>
            </div>
            <h2 class="text-4xl md:text-6xl font-black mb-6">
                    <span class="bg-gradient-to-r from-white via-slate-200 to-white bg-clip-text text-transparent">
                        {{ __('Get Started in Minutes') }}
                    </span>
            </h2>
            <p class="text-xl text-slate-400 max-w-3xl mx-auto">
                {{ __('Transform your procurement process in 3 simple steps') }}
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 relative">
            <!-- Connecting Lines (Desktop) -->
            <div class="hidden md:block absolute top-24 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-purple-500/30 to-transparent"></div>

            <!-- Step 1 -->
            <div class="relative">
                <div class="relative z-10 p-8 bg-gradient-to-br from-purple-950/30 to-pink-950/30 backdrop-blur-xl rounded-2xl border border-purple-500/20 hover:border-purple-400/40 transition-all">
                    <div class="absolute -top-6 left-8">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/50">
                            <span class="text-2xl font-black text-white">1</span>
                        </div>
                    </div>
                    <div class="mt-8">
                        <h3 class="text-xl font-bold mb-3 text-white">{{ __('Create Account') }}</h3>
                        <p class="text-slate-400">{{ __('Sign up in seconds and configure your enterprise procurement workspace with role-based access.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="relative">
                <div class="relative z-10 p-8 bg-gradient-to-br from-blue-950/30 to-purple-950/30 backdrop-blur-xl rounded-2xl border border-blue-500/20 hover:border-blue-400/40 transition-all">
                    <div class="absolute -top-6 left-8">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/50">
                            <span class="text-2xl font-black text-white">2</span>
                        </div>
                    </div>
                    <div class="mt-8">
                        <h3 class="text-xl font-bold mb-3 text-white">{{ __('Connect Suppliers') }}</h3>
                        <p class="text-slate-400">{{ __('Invite your suppliers or discover new ones from our verified global marketplace.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="relative">
                <div class="relative z-10 p-8 bg-gradient-to-br from-cyan-950/30 to-blue-950/30 backdrop-blur-xl rounded-2xl border border-cyan-500/20 hover:border-cyan-400/40 transition-all">
                    <div class="absolute -top-6 left-8">
                        <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-cyan-500/50">
                            <span class="text-2xl font-black text-white">3</span>
                        </div>
                    </div>
                    <div class="mt-8">
                        <h3 class="text-xl font-bold mb-3 text-white">{{ __('Start Procuring') }}</h3>
                        <p class="text-slate-400">{{ __('Create RFQs, receive quotes, and manage orders—all powered by AI automation.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section id="pricing" class="py-24 md:py-32 bg-gradient-to-b from-black via-slate-950 to-black relative overflow-hidden">
    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 50% 50%, rgba(6, 182, 212, 0.05) 1px, transparent 1px); background-size: 50px 50px;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
            <div class="inline-flex items-center gap-2 px-4 py-2 mb-6 bg-blue-500/10 border border-blue-500/20 rounded-full">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-bold text-blue-400">{{ __('PRICING') }}</span>
            </div>
            <h2 class="text-4xl md:text-6xl font-black mb-6">
                    <span class="bg-gradient-to-r from-white via-slate-200 to-white bg-clip-text text-transparent">
                        {{ __('Choose Your Plan') }}
                    </span>
            </h2>
            <p class="text-xl text-slate-400 max-w-3xl mx-auto">
                {{ __('Flexible pricing for businesses of all sizes') }}
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <!-- Starter Plan -->
            <div class="p-8 bg-gradient-to-br from-slate-950/50 to-slate-900/50 backdrop-blur-xl rounded-2xl border border-white/10 hover:border-white/20 transition-all">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-black text-white mb-2">{{ __('Starter') }}</h3>
                    <div class="text-4xl font-black text-white mb-2">
                        {{ __('Free') }}
                    </div>
                    <p class="text-sm text-slate-400">{{ __('Perfect for small teams') }}</p>
                </div>
                <ul class="space-y-4 mb-8" role="list">
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-cyan-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-slate-300">{{ __('Up to 10 RFQs per month') }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-cyan-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-slate-300">{{ __('5 supplier connections') }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-cyan-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-slate-300">{{ __('Basic analytics') }}</span>
                    </li>
                </ul>
                <a href="{{ route('register') }}" class="block w-full py-3 text-center font-semibold text-white bg-white/10 hover:bg-white/20 rounded-xl transition focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50">
                    {{ __('Get Started') }}
                </a>
            </div>

            <!-- Professional Plan (Featured) -->
            <div class="relative bg-gradient-to-br from-cyan-600/20 via-blue-600/20 to-purple-600/20 p-8 rounded-2xl border-2 border-cyan-500/50 transform scale-105 shadow-2xl shadow-cyan-500/20">
                <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                    <span class="px-4 py-1 bg-gradient-to-r from-cyan-500 to-blue-500 text-white text-xs font-bold rounded-full shadow-lg">{{ __('MOST POPULAR') }}</span>
                </div>
                <div class="text-center mb-8 text-white">
                    <h3 class="text-2xl font-black mb-2">{{ __('Professional') }}</h3>
                    <div class="text-5xl font-black mb-2 bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                        $99<span class="text-lg font-medium text-white">/{{ __('mo') }}</span>
                    </div>
                    <p class="text-sm text-cyan-100">{{ __('For growing businesses') }}</p>
                </div>
                <ul class="space-y-4 mb-8 text-white" role="list">
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ __('Unlimited RFQs') }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ __('Unlimited suppliers') }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ __('Advanced analytics') }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ __('Priority support 24/7') }}</span>
                    </li>
                </ul>
                <a href="{{ route('register') }}" class="block w-full py-3 text-center font-bold text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 rounded-xl transition shadow-lg focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50">
                    {{ __('Start Free Trial') }}
                </a>
            </div>

            <!-- Enterprise Plan -->
            <div class="p-8 bg-gradient-to-br from-slate-950/50 to-slate-900/50 backdrop-blur-xl rounded-2xl border border-white/10 hover:border-white/20 transition-all">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-black text-white mb-2">{{ __('Enterprise') }}</h3>
                    <div class="text-4xl font-black text-white mb-2">
                        {{ __('Custom') }}
                    </div>
                    <p class="text-sm text-slate-400">{{ __('For large organizations') }}</p>
                </div>
                <ul class="space-y-4 mb-8" role="list">
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-purple-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-slate-300">{{ __('Everything in Pro') }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-purple-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-slate-300">{{ __('Dedicated account manager') }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-purple-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-slate-300">{{ __('Custom integrations') }}</span>
                    </li>
                </ul>
                <a href="mailto:sales@dpanel.com" class="block w-full py-3 text-center font-semibold text-white bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 rounded-xl transition focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50">
                    {{ __('Contact Sales') }}
                </a>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 md:py-32 bg-gradient-to-r from-cyan-600 via-blue-600 to-purple-600 relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0zNiAxOGMzLjMxIDAgNiAyLjY5IDYgNnMtMi42OSA2LTYgNi02LTIuNjktNi02IDIuNjktNiA2LTZ6TTI0IDEyYzIuMjEgMCA0IDEuNzkgNCA0cy0xLjc5IDQtNCA0LTQtMS43OS00LTQgMS43OS00IDQtNHoiIGZpbGw9IiNmZmYiIG9wYWNpdHk9Ii4xIi8+PC9nPjwvc3ZnPg==')] opacity-10"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl md:text-6xl font-black text-white mb-6">
            {{ __('Ready to Transform Your Procurement?') }}
        </h2>
        <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto">
            {{ __('Join 10,000+ companies already streamlining their procurement with dPanel.') }}
        </p>
        <a href="{{ route('register') }}" class="group inline-flex items-center gap-3 px-10 py-5 text-lg font-bold text-blue-600 bg-white hover:bg-blue-50 rounded-xl shadow-2xl hover:shadow-white/30 transition-all transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-white focus:ring-opacity-50">
            {{ __('Start Your Free Trial') }}
            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
        </a>
    </div>
</section>

<!-- Footer -->
<footer class="bg-black text-slate-400 py-12 border-t border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <p class="text-sm">&copy; {{ date('Y') }} dPanel. {{ __('All rights reserved.') }}</p>
            </div>
            <div class="flex gap-6">
                <a href="#" class="text-sm hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-1">{{ __('Privacy') }}</a>
                <a href="#" class="text-sm hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-1">{{ __('Terms') }}</a>
                <a href="#" class="text-sm hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-1">{{ __('Contact') }}</a>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
