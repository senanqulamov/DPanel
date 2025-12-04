<div class="space-y-6">
    {{-- Hero / Summary --}}
    <x-card class="bg-gradient-to-r from-emerald-600 via-emerald-500 to-cyan-500 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <x-icon name="shopping-bag" class="w-7 h-7" />
                    <h1 class="text-2xl md:text-3xl font-semibold tracking-tight">
                        {{ __('Seller Dashboard') }}
                    </h1>
                </div>
                <p class="text-sm md:text-base text-emerald-50/90 max-w-xl">
                    {{ __('Monitor your marketplace performance, manage products, and track orders in one unified view.') }}
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full md:w-auto">
                <div class="bg-emerald-900/30 rounded-lg px-3 py-2 border border-emerald-400/40">
                    <p class="text-[10px] uppercase tracking-wide text-emerald-100/70 mb-1">{{ __('Total Revenue') }}</p>
                    <p class="text-lg font-semibold leading-tight">
                        {{ '$' . number_format($totalRevenue, 2) }}
                    </p>
                </div>
                <div class="bg-emerald-900/30 rounded-lg px-3 py-2 border border-emerald-400/40">
                    <p class="text-[10px] uppercase tracking-wide text-emerald-100/70 mb-1">{{ __('Total Sales') }}</p>
                    <p class="text-lg font-semibold leading-tight">{{ $totalSales }}</p>
                </div>
                <div class="bg-emerald-900/30 rounded-lg px-3 py-2 border border-emerald-400/40">
                    <p class="text-[10px] uppercase tracking-wide text-emerald-100/70 mb-1">{{ __('Active Markets') }}</p>
                    <p class="text-lg font-semibold leading-tight">{{ $activeMarkets }}</p>
                </div>
                <div class="bg-emerald-900/30 rounded-lg px-3 py-2 border border-emerald-400/40">
                    <p class="text-[10px] uppercase tracking-wide text-emerald-100/70 mb-1">{{ __('Products Listed') }}</p>
                    <p class="text-lg font-semibold leading-tight">{{ $productsListed }}</p>
                </div>
            </div>
        </div>
    </x-card>

    {{-- Seller nav --}}
    <x-seller.nav />

    {{-- KPI grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <x-card>
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Commission Earned') }}</p>
                    <p class="mt-1 text-xl font-semibold text-gray-900 dark:text-gray-50">
                        {{ '$' . number_format($commissionEarned, 2) }}
                    </p>
                </div>
                <x-icon name="banknotes" class="w-7 h-7 text-emerald-500" />
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ __('Based on your current commission rate and completed orders.') }}
            </p>
        </x-card>

        <x-card>
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Pending Orders') }}</p>
                    <p class="mt-1 text-xl font-semibold text-gray-900 dark:text-gray-50">
                        {{ $pendingOrders }}
                    </p>
                </div>
                <x-icon name="clock" class="w-7 h-7 text-amber-500" />
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ __('Orders awaiting fulfillment or confirmation.') }}
            </p>
        </x-card>

        <x-card>
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Seller Rating') }}</p>
                    <p class="mt-1 text-xl font-semibold text-gray-900 dark:text-gray-50">
                        {{ number_format($averageRating, 1) }}/5.0
                    </p>
                </div>
                <x-icon name="star" class="w-7 h-7 text-yellow-400" />
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                <div class="bg-yellow-400 h-1.5 rounded-full" style="width: {{ min(100, ($averageRating / 5) * 100) }}%"></div>
            </div>
            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                {{ __('Build trust by keeping a high fulfillment and response rate.') }}
            </p>
        </x-card>

        <x-card>
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Verification') }}</p>
                    <p class="mt-1 text-xl font-semibold text-gray-900 dark:text-gray-50">
                        {{ auth()->user()->verified_seller ? __('Verified') : __('Not Verified') }}
                    </p>
                </div>
                <x-icon name="shield-check" class="w-7 h-7 {{ auth()->user()->verified_seller ? 'text-emerald-500' : 'text-gray-400' }}" />
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ __('Verified sellers gain higher visibility and customer confidence.') }}
            </p>
        </x-card>
    </div>

    {{-- Quick Actions as cards --}}
    <x-card class="bg-slate-950/60 border-slate-800">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <x-icon name="bolt" class="w-5 h-5 text-yellow-400" />
                <h2 class="text-sm font-semibold text-slate-50">{{ __('Quick Actions') }}</h2>
            </div>
            <p class="text-[11px] text-slate-400 hidden md:block">
                {{ __('Jump to your most common seller workflows.') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <a href="{{ route('seller.products.index') }}" class="group rounded-lg bg-slate-900/80 hover:bg-slate-800 transition border border-slate-800 hover:border-emerald-500/60 p-4 flex flex-col justify-between">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-full bg-emerald-500/15 flex items-center justify-center">
                        <x-icon name="cube" class="w-4 h-4 text-emerald-400" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-50">{{ __('Manage Products') }}</p>
                        <p class="text-[11px] text-slate-400">{{ __('Create, update, and organize your catalog.') }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-400">
                    <span>{{ __('Go to products') }}</span>
                    <x-icon name="arrow-right" class="w-4 h-4 text-slate-400 group-hover:text-emerald-400" />
                </div>
            </a>

            <a href="{{ route('seller.orders.index') }}" class="group rounded-lg bg-slate-900/80 hover:bg-slate-800 transition border border-slate-800 hover:border-sky-500/60 p-4 flex flex-col justify-between">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-full bg-sky-500/15 flex items-center justify-center">
                        <x-icon name="receipt-percent" class="w-4 h-4 text-sky-400" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-50">{{ __('View Orders') }}</p>
                        <p class="text-[11px] text-slate-400">{{ __('Track incoming orders and fulfillment status.') }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-400">
                    <span>{{ __('Go to orders') }}</span>
                    <x-icon name="arrow-right" class="w-4 h-4 text-slate-400 group-hover:text-sky-400" />
                </div>
            </a>

            <a href="{{ route('seller.markets.index') }}" class="group rounded-lg bg-slate-900/80 hover:bg-slate-800 transition border border-slate-800 hover:border-purple-500/60 p-4 flex flex-col justify-between">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-full bg-purple-500/15 flex items-center justify-center">
                        <x-icon name="building-storefront" class="w-4 h-4 text-purple-400" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-50">{{ __('Browse Markets') }}</p>
                        <p class="text-[11px] text-slate-400">{{ __('Configure and optimize your sales channels.') }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-400">
                    <span>{{ __('Go to markets') }}</span>
                    <x-icon name="arrow-right" class="w-4 h-4 text-slate-400 group-hover:text-purple-400" />
                </div>
            </a>

            <a href="{{ route('seller.logs.index') }}" class="group rounded-lg bg-slate-900/80 hover:bg-slate-800 transition border border-slate-800 hover:border-indigo-500/60 p-4 flex flex-col justify-between">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-full bg-indigo-500/15 flex items-center justify-center">
                        <x-icon name="clipboard-document-list" class="w-4 h-4 text-indigo-400" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-50">{{ __('View Activity Log') }}</p>
                        <p class="text-[11px] text-slate-400">{{ __('See changes and events related to your account.') }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-400">
                    <span>{{ __('Go to activity') }}</span>
                    <x-icon name="arrow-right" class="w-4 h-4 text-slate-400 group-hover:text-indigo-400" />
                </div>
            </a>

            <a href="{{ route('settings.index') }}" class="group rounded-lg bg-slate-900/80 hover:bg-slate-800 transition border border-slate-800 hover:border-gray-500/60 p-4 flex flex-col justify-between">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-full bg-slate-500/15 flex items-center justify-center">
                        <x-icon name="cog-6-tooth" class="w-4 h-4 text-gray-200" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-50">{{ __('Settings') }}</p>
                        <p class="text-[11px] text-slate-400">{{ __('Update profile, preferences, and verification.') }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-400">
                    <span>{{ __('Open settings') }}</span>
                    <x-icon name="arrow-right" class="w-4 h-4 text-slate-400 group-hover:text-gray-200" />
                </div>
            </a>
        </div>
    </x-card>

    {{-- Performance view under quick actions --}}
    <x-card>
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <x-icon name="chart-bar" class="w-5 h-5 text-emerald-500" />
                <h2 class="text-sm font-semibold text-gray-900 dark:text-gray-50">{{ __('Performance Overview') }}</h2>
            </div>
            <p class="text-[11px] text-gray-500 dark:text-gray-400">
                {{ __('Snapshot of your catalog and order flow.') }}
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Products card --}}
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        {{ __('Products Listed') }}
                    </p>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-purple-500/10 text-purple-400 border border-purple-500/30">
                        {{ __('Catalog') }}
                    </span>
                </div>
                <p class="text-xl font-semibold text-gray-900 dark:text-gray-50">{{ $productsListed }}</p>
                <div class="w-full bg-gray-200 dark:bg-gray-800 rounded-full h-2 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-500 to-fuchsia-500 h-2" style="width: {{ min(100, ($productsListed / 50) * 100) }}%"></div>
                </div>
                <p class="text-[11px] text-gray-500 dark:text-gray-400">
                    {{ __('More quality products improve your visibility across markets.') }}
                </p>
            </div>

            {{-- Sales card --}}
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        {{ __('Total Sales') }}
                    </p>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                        {{ __('Orders') }}
                    </span>
                </div>
                <p class="text-xl font-semibold text-gray-900 dark:text-gray-50">{{ $totalSales }}</p>
                <div class="w-full bg-gray-200 dark:bg-gray-800 rounded-full h-2 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-500 to-lime-500 h-2" style="width: {{ min(100, ($totalSales / 100) * 100) }}%"></div>
                </div>
                <p class="text-[11px] text-gray-500 dark:text-gray-400">
                    {{ __('Represents completed orders containing your products.') }}
                </p>
            </div>

            {{-- Pending card --}}
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        {{ __('Pending Orders') }}
                    </p>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/30">
                        {{ __('Queue') }}
                    </span>
                </div>
                <p class="text-xl font-semibold text-gray-900 dark:text-gray-50">{{ $pendingOrders }}</p>
                <div class="w-full bg-gray-200 dark:bg-gray-800 rounded-full h-2 overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-500 to-orange-500 h-2" style="width: {{ min(100, ($pendingOrders / 50) * 100) }}%"></div>
                </div>
                <p class="text-[11px] text-gray-500 dark:text-gray-400">
                    {{ __('Lower is better – keep this small with fast fulfillment.') }}
                </p>
            </div>
        </div>
    </x-card>

    {{-- Bottom: guidance + verification --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
        @if($productsListed === 0)
            <x-card class="bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800">
                <div class="flex items-start gap-4">
                    <x-icon name="light-bulb" class="w-8 h-8 text-emerald-500" />
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-50 mb-1">
                            {{ __('Start Selling Today') }}
                        </h3>
                        <p class="text-xs text-gray-600 dark:text-gray-300 mb-3">
                            {{ __('List your first product to start reaching buyers across all your markets.') }}
                        </p>
                        <a href="{{ route('seller.products.index') }}" class="inline-flex items-center px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded-md transition">
                            <x-icon name="plus" class="w-4 h-4 mr-1.5" />
                            {{ __('Add Product') }}
                        </a>
                    </div>
                </div>
            </x-card>
        @endif

        @if(!auth()->user()->verified_seller)
            <x-card class="bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800">
                <div class="flex items-start gap-4">
                    <x-icon name="exclamation-triangle" class="w-7 h-7 text-amber-500" />
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-50 mb-1">
                            {{ __('Verify Your Seller Account') }}
                        </h3>
                        <p class="text-xs text-gray-600 dark:text-gray-300 mb-3">
                            {{ __('Complete verification to unlock higher limits, priority placement, and trust badges.') }}
                        </p>
                        <a href="{{ route('settings.index') }}" class="inline-flex items-center text-xs font-medium text-amber-700 dark:text-amber-300 hover:text-amber-900 dark:hover:text-amber-100">
                            {{ __('Start Verification') }}
                            <x-icon name="arrow-right" class="w-4 h-4 ml-1" />
                        </a>
                    </div>
                </div>
            </x-card>
        @endif
    </div>
</div>
