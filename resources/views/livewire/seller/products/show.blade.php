<div class="space-y-6">
    {{-- Modern Header Card - 2026 Style --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-600 via-purple-500 to-fuchsia-500 text-white shadow-2xl shadow-purple-500/30">
        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>

        <div class="relative p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30">
                        <x-icon name="cube" class="w-7 h-7 text-white" />
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold tracking-tight">
                            {{ $product->name }}
                        </h1>
                        <p class="text-sm text-purple-100 mt-0.5">
                            {{ __('SKU:') }} {{ $product->sku }}
                        </p>
                    </div>
                </div>

                <div class="flex gap-2">
                    <x-button icon="arrow-left" href="{{ route('seller.products.index') }}" color="white">
                        @lang('Back to Products')
                    </x-button>
                    <x-button icon="pencil" wire:click="$dispatch('load::product', { product: '{{ $product->id }}' })" color="white">
                        @lang('Edit Product')
                    </x-button>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Grid - 2026 Style --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Units Sold --}}
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500/10 via-emerald-500/5 to-transparent dark:from-emerald-500/20 dark:via-emerald-500/10 backdrop-blur-sm border border-emerald-200/50 dark:border-emerald-500/30 hover:border-emerald-400/60 transition-all duration-300 hover:shadow-lg hover:shadow-emerald-500/20">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/0 to-emerald-600/0 group-hover:from-emerald-400/5 group-hover:to-emerald-600/5 transition-all duration-500"></div>
            <div class="relative p-5">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex-1">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">{{ __('Units Sold') }}</p>
                        <p class="text-2xl font-bold bg-gradient-to-br from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent mt-1">
                            {{ number_format($this->metrics['total_sold']) }}
                        </p>
                    </div>
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center">
                        <x-icon name="shopping-bag" class="w-5 h-5 text-white" />
                    </div>
                </div>
            </div>
        </div>

        {{-- Revenue --}}
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500/10 via-blue-500/5 to-transparent dark:from-blue-500/20 dark:via-blue-500/10 backdrop-blur-sm border border-blue-200/50 dark:border-blue-500/30 hover:border-blue-400/60 transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/20">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-400/0 to-blue-600/0 group-hover:from-blue-400/5 group-hover:to-blue-600/5 transition-all duration-500"></div>
            <div class="relative p-5">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex-1">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">{{ __('Revenue') }}</p>
                        <p class="text-2xl font-bold bg-gradient-to-br from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent mt-1">
                            ${{ number_format($this->metrics['revenue'], 2) }}
                        </p>
                    </div>
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                        <x-icon name="banknotes" class="w-5 h-5 text-white" />
                    </div>
                </div>
            </div>
        </div>

        {{-- Avg Price --}}
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500/10 via-purple-500/5 to-transparent dark:from-purple-500/20 dark:via-purple-500/10 backdrop-blur-sm border border-purple-200/50 dark:border-purple-500/30 hover:border-purple-400/60 transition-all duration-300 hover:shadow-lg hover:shadow-purple-500/20">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-400/0 to-purple-600/0 group-hover:from-purple-400/5 group-hover:to-purple-600/5 transition-all duration-500"></div>
            <div class="relative p-5">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex-1">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400">{{ __('Avg Price') }}</p>
                        <p class="text-2xl font-bold bg-gradient-to-br from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent mt-1">
                            ${{ number_format($this->metrics['avg_price'], 2) }}
                        </p>
                    </div>
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center">
                        <x-icon name="chart-bar" class="w-5 h-5 text-white" />
                    </div>
                </div>
            </div>
        </div>

        {{-- Stock --}}
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500/10 via-amber-500/5 to-transparent dark:from-amber-500/20 dark:via-amber-500/10 backdrop-blur-sm border border-amber-200/50 dark:border-amber-500/30 hover:border-amber-400/60 transition-all duration-300 hover:shadow-lg hover:shadow-amber-500/20">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-400/0 to-amber-600/0 group-hover:from-amber-400/5 group-hover:to-amber-600/5 transition-all duration-500"></div>
            <div class="relative p-5">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex-1">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">{{ __('Stock') }}</p>
                        <p class="text-2xl font-bold bg-gradient-to-br from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent mt-1">
                            {{ number_format($this->metrics['stock']) }}
                        </p>
                    </div>
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center">
                        <x-icon name="archive-box" class="w-5 h-5 text-white" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Details Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Product Details Card --}}
        <div class="relative overflow-hidden rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-gray-200/50 dark:border-slate-700/50 shadow-lg">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-fuchsia-500/5 dark:from-purple-500/10 dark:to-fuchsia-500/10"></div>
            <div class="relative p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-50 mb-4 flex items-center gap-2">
                    <x-icon name="information-circle" class="w-5 h-5 text-purple-500" />
                    @lang('Product Details')
                </h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between items-center p-3 rounded-xl bg-gray-50/50 dark:bg-slate-800/50">
                        <dt class="text-gray-600 dark:text-gray-400 font-medium">@lang('Category')</dt>
                        <dd class="font-semibold text-gray-900 dark:text-gray-100">{{ $product->category?->name ?? __('N/A') }}</dd>
                    </div>
                    <div class="flex justify-between items-center p-3 rounded-xl bg-gray-50/50 dark:bg-slate-800/50">
                        <dt class="text-gray-600 dark:text-gray-400 font-medium">@lang('Price')</dt>
                        <dd class="font-semibold text-gray-900 dark:text-gray-100">${{ number_format($product->price, 2) }}</dd>
                    </div>
                    <div class="flex justify-between items-center p-3 rounded-xl bg-gray-50/50 dark:bg-slate-800/50">
                        <dt class="text-gray-600 dark:text-gray-400 font-medium">@lang('Created')</dt>
                        <dd class="font-semibold text-gray-900 dark:text-gray-100">{{ $product->created_at->toDayDateTimeString() }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Market Card --}}
        <div class="relative overflow-hidden rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-gray-200/50 dark:border-slate-700/50 shadow-lg">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-purple-500/5 dark:from-indigo-500/10 dark:to-purple-500/10"></div>
            <div class="relative p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-50 mb-4 flex items-center gap-2">
                    <x-icon name="building-storefront" class="w-5 h-5 text-indigo-500" />
                    @lang('Market')
                </h3>
                @if($product->market)
                    <div class="space-y-4">
                        <div class="p-4 rounded-xl bg-gradient-to-br from-indigo-50/80 to-purple-50/80 dark:from-indigo-900/20 dark:to-purple-900/20 border border-indigo-200/50 dark:border-indigo-700/50">
                            <p class="font-bold text-gray-900 dark:text-gray-100 mb-1">{{ $product->market->name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $product->market->location }}</p>
                        </div>
                        <x-button size="sm" href="{{ route('seller.markets.show', $product->market) }}" icon="arrow-right" class="w-full">
                            @lang('View Market')
                        </x-button>
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 p-4 rounded-xl bg-gray-50/50 dark:bg-slate-800/50">@lang('No market associated yet.')</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Product Attributes Section --}}
    @if($product->attributes->count() > 0)
    <div class="relative overflow-hidden rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-gray-200/50 dark:border-slate-700/50 shadow-lg">
        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-blue-500/5 dark:from-cyan-500/10 dark:to-blue-500/10"></div>
        <div class="relative p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-50 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                </svg>
                @lang('Product Specifications')
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($product->attributes as $attribute)
                    <div class="group relative p-4 bg-gradient-to-br from-white to-gray-50 dark:from-slate-800 dark:to-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl hover:border-cyan-300 dark:hover:border-cyan-600 hover:shadow-lg hover:shadow-cyan-500/20 transition-all duration-300">
                        <dt class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                            {{ $attribute->name }}
                        </dt>
                        <dd class="text-sm font-semibold text-gray-900 dark:text-gray-100 break-words">
                            {{ $attribute->value }}
                        </dd>
                        <div class="absolute inset-0 bg-gradient-to-r from-cyan-50/50 to-blue-50/50 dark:from-cyan-900/10 dark:to-blue-900/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity -z-10"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Recent Orders Table --}}
    <div class="relative overflow-hidden rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-gray-200/50 dark:border-slate-700/50 shadow-xl">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 via-transparent to-cyan-500/5 dark:from-blue-500/10 dark:to-cyan-500/10"></div>

        <div class="relative p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center">
                    <x-icon name="shopping-cart" class="w-5 h-5 text-white" />
                </div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">@lang('Recent Orders')</h2>
            </div>

            <x-table :headers="[
                ['index' => 'order_number', 'label' => __('Order Number')],
                ['index' => 'user', 'label' => __('Supplier'), 'sortable' => false],
                ['index' => 'markets', 'label' => __('Markets'), 'sortable' => false],
                ['index' => 'quantity', 'label' => __('Qty')],
                ['index' => 'subtotal', 'label' => __('Subtotal')],
                ['index' => 'created_at', 'label' => __('Created')],
            ]" :rows="$this->orders" paginate :paginator="null" loading>
                @interact('column_order_number', $row)
                <a href="{{ route('seller.orders.show', $row) }}" class="text-blue-600 hover:underline">
                    <x-badge text="{{ $row->order_number }}" icon="eye" position="left"/>
                </a>
                @endinteract

                @interact('column_user', $row)
                <div class="flex flex-col">
                    <span class="font-medium">{{ $row->user?->name ?? __('Guest') }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $row->user?->email }}</span>
                </div>
                @endinteract

                @interact('column_markets', $row)
                <div class="flex flex-wrap gap-1">
                    @foreach($row->markets as $market)
                        <x-badge :text="$market->name" size="sm" />
                    @endforeach
                </div>
                @endinteract

                @interact('column_quantity', $row)
                {{ optional($row->pivot)->quantity ?? $row->items->where('product_id', $product->id)->sum('quantity') }}
                @endinteract

                @interact('column_subtotal', $row)
                ${{ number_format(optional($row->pivot)->subtotal ?? $row->items->where('product_id', $product->id)->sum('subtotal'), 2) }}
                @endinteract

                @interact('column_created_at', $row)
                {{ $row->created_at->diffForHumans() }}
                @endinteract
            </x-table>
        </div>
    </div>

    <livewire:products.update @updated="$refresh" />
</div>
