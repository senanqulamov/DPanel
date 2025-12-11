@props(['role' => 'default'])

@php
    $roleConfig = [
        'buyer' => [
            'gradient' => 'from-blue-600 via-blue-500 to-indigo-600',
            'icon' => 'shopping-cart',
            'title' => __('Buyer Portal'),
            'accent' => 'blue',
        ],
        'seller' => [
            'gradient' => 'from-emerald-600 via-emerald-500 to-green-600',
            'icon' => 'shopping-bag',
            'title' => __('Seller Portal'),
            'accent' => 'emerald',
        ],
        'supplier' => [
            'gradient' => 'from-purple-600 via-purple-500 to-indigo-600',
            'icon' => 'building-office',
            'title' => __('Supplier Portal'),
            'accent' => 'purple',
        ],
        'admin' => [
            'gradient' => 'from-red-600 via-orange-500 to-amber-600',
            'icon' => 'shield-check',
            'title' => __('Admin Portal'),
            'accent' => 'red',
        ],
    ];

    $config = $roleConfig[$role] ?? $roleConfig['admin'];
@endphp

<header class="flex-shrink-0 backdrop-blur-xl bg-slate-950/90 border-b border-slate-800/50 shadow-2xl z-30">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Left Section --}}
            <div class="flex items-center gap-4">
                {{-- Mobile Menu Toggle --}}
                <button x-data x-on:click="$dispatch('sidebar-toggle')"
                        class="lg:hidden p-2 rounded-lg bg-slate-800/50 hover:bg-slate-700/50 transition">
                    <x-icon name="bars-3" class="w-5 h-5 text-slate-300" />
                </button>

                {{-- Portal Badge --}}
                <div class="hidden md:flex items-center gap-3 px-4 py-2 rounded-xl bg-gradient-to-r {{ $config['gradient'] }} shadow-lg">
                    <x-icon name="{{ $config['icon'] }}" class="w-5 h-5 text-white" />
                    <span class="text-sm font-bold text-white tracking-wide">{{ $config['title'] }}</span>
                </div>

                {{ $left ?? '' }}
            </div>

            {{-- Center Section - Search --}}
            <div class="hidden lg:flex flex-1 max-w-xl mx-8">
                <div class="relative w-full">
                    <x-icon name="magnifying-glass" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
                    <input type="search"
                           placeholder="{{ __('Search...') }}"
                           class="w-full pl-12 pr-4 py-2.5 bg-slate-800/50 border border-slate-700/50 rounded-xl text-sm text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-{{ $config['accent'] }}-500/50 focus:border-{{ $config['accent'] }}-500 transition">
                </div>
            </div>

            {{-- Right Section --}}
            <div class="flex items-center gap-3">
                {{-- Notifications --}}
                <button class="relative p-2.5 rounded-xl bg-slate-800/50 hover:bg-slate-700/50 transition group">
                    <x-icon name="bell" class="w-5 h-5 text-slate-300 group-hover:text-white transition" />
                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-{{ $config['accent'] }}-500 rounded-full text-xs font-bold text-white flex items-center justify-center shadow-lg">3</span>
                </button>

                {{-- Language Switcher --}}
                <div class="hidden sm:block" x-data="{ open: false }" x-on:click.away="open = false">
                    <button x-on:click="open = !open"
                            class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-800/50 hover:bg-slate-700/50 transition">
                        <span class="text-xs font-medium text-slate-300">{{ strtoupper(app()->getLocale()) }}</span>
                        <x-icon name="language" class="w-4 h-4 text-slate-400" />
                    </button>

                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-30 mt-2 w-48 rounded-xl bg-slate-800 border border-slate-700 shadow-2xl overflow-hidden z-50"
                         style="display: none;">
                        @foreach(['en' => 'English', 'de' => 'Deutsch', 'es' => 'Español', 'fr' => 'Français', 'tr' => 'Türkçe', 'az' => 'Azərbaycanca'] as $code => $name)
                            <a href="{{ route('lang.switch', $code) }}"
                               class="flex items-center gap-3 px-4 py-3 hover:bg-slate-700/50 transition {{ app()->getLocale() === $code ? 'bg-slate-700/30 text-white' : 'text-slate-300' }}">
                                <span class="text-sm font-medium">{{ $name }}</span>
                                @if(app()->getLocale() === $code)
                                    <x-icon name="check" class="w-4 h-4 text-{{ $config['accent'] }}-500 ml-auto" />
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- User Menu --}}
                {{ $right ?? '' }}
            </div>
        </div>
    </div>
</header>
