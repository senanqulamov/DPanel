<div class="space-y-6">
    <x-supplier.nav />

    {{-- Modern Header Card --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-cyan-600 via-cyan-500 to-blue-500 text-white shadow-2xl shadow-cyan-500/30">
        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>

        <div class="relative p-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('supplier.markets.index') }}" class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30 hover:bg-white/30 transition">
                    <x-icon name="arrow-left" class="w-5 h-5 text-white" />
                </a>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold tracking-tight">
                        {{ $market->name }}
                    </h1>
                    <p class="text-sm text-cyan-100 mt-0.5">
                        {{ __('Market Details') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Market Details --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="relative overflow-hidden rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-gray-200/50 dark:border-slate-700/50 shadow-xl p-6">
                <h2 class="text-xl font-bold mb-4">{{ __('Market Information') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-400">{{ __('Location') }}</label>
                        <p class="font-medium">{{ $market->location ?? '—' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-400">{{ __('Seller') }}</label>
                        <p class="font-medium">{{ $market->seller?->name ?? '—' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Products') }}</label>
                        <p class="font-bold text-xl text-cyan-600">{{ $market->products->count() }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-400">{{ __('Created') }}</label>
                        <p class="font-medium">{{ $market->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                @if($market->description)
                    <div class="mt-4">
                        <label class="text-sm text-gray-600 dark:text-gray-400">{{ __('Description') }}</label>
                        <p class="mt-1">{{ $market->description }}</p>
                    </div>
                @endif
            </div>

            {{-- Products in this Market --}}
            @if($market->products->count() > 0)
                <div class="relative overflow-hidden rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-gray-200/50 dark:border-slate-700/50 shadow-xl p-6">
                    <h2 class="text-xl font-bold mb-4">{{ __('Products in this Market') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($market->products->take(6) as $product)
                            <div class="p-4 rounded-xl border border-gray-200 dark:border-slate-700 hover:border-cyan-500 transition">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-semibold">{{ $product->name }}</h3>
                                    <span class="font-bold text-cyan-600">${{ number_format($product->price, 2) }}</span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $product->category }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ __('Stock') }}: {{ $product->stock }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <div class="relative overflow-hidden rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-gray-200/50 dark:border-slate-700/50 shadow-xl p-6">
                <h3 class="text-lg font-bold mb-4">{{ __('Market Stats') }}</h3>
                <div class="space-y-4">
                    <div class="p-3 rounded-lg bg-cyan-50 dark:bg-cyan-900/20">
                        <div class="text-xs text-cyan-600 dark:text-cyan-400 mb-1">{{ __('Products') }}</div>
                        <div class="text-2xl font-bold">{{ $market->products->count() }}</div>
                    </div>
                    <div class="p-3 rounded-lg bg-green-50 dark:bg-green-900/20">
                        <div class="text-xs text-green-600 dark:text-green-400 mb-1">{{ __('In Stock') }}</div>
                        <div class="text-2xl font-bold">{{ $market->products->where('stock', '>', 0)->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
