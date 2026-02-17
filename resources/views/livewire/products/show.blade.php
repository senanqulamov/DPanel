<div>
    <x-card>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $product->name }}</h1>
                <p class="text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }}</p>
            </div>

            <div class="flex gap-2">
                <x-button icon="arrow-left" href="{{ route('products.index') }}">
                    @lang('Back to Products')
                </x-button>
                <x-button icon="pencil" wire:click="$dispatch('load::product', { product: '{{ $product->id }}' })">
                    @lang('Edit Product')
                </x-button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
            <x-stat :label="__('Units Sold')" :value="number_format($this->metrics['total_sold'])" icon="shopping-bag" />
            <x-stat :label="__('Revenue')" :value="'$' . number_format($this->metrics['revenue'], 2)" icon="banknotes" />
            <x-stat :label="__('Avg Price')" :value="'$' . number_format($this->metrics['avg_price'], 2)" icon="chart-bar" />
            <x-stat :label="__('Stock')" :value="number_format($this->metrics['stock'])" icon="archive-box" />
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-card variant="subtle">
                <x-slot name="title">@lang('Product Details')</x-slot>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">@lang('Category')</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $product->category?->name ?? __('N/A') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">@lang('Price')</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">${{ number_format($product->price, 2) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">@lang('Created')</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $product->created_at->toDayDateTimeString() }}</dd>
                    </div>
                </dl>
            </x-card>

            <x-card variant="subtle">
                <x-slot name="title">@lang('Market')</x-slot>
                @if($product->market)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $product->market->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $product->market->location }}</p>
                        </div>
                        <x-button size="sm" href="{{ route('markets.show', $product->market) }}" icon="arrow-right">
                            @lang('View Market')
                        </x-button>
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">@lang('No market associated yet.')</p>
                @endif
            </x-card>
        </div>

        @if($product->attributes->count() > 0)
        <div class="mt-6">
            <x-card variant="subtle">
                <x-slot name="title">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                        </svg>
                        @lang('Product Specifications')
                    </div>
                </x-slot>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($product->attributes as $attribute)
                        <div class="group relative p-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-sm transition-all duration-200">
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                                {{ $attribute->name }}
                            </dt>
                            <dd class="text-sm font-semibold text-gray-900 dark:text-gray-100 break-words">
                                {{ $attribute->value }}
                            </dd>
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-50/50 to-purple-50/50 dark:from-blue-900/5 dark:to-purple-900/5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity -z-10"></div>
                        </div>
                    @endforeach
                </div>
            </x-card>
        </div>
        @endif

        <div class="mt-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">@lang('Recent Orders')</h2>
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
                <a href="{{ route('orders.show', $row) }}" class="text-blue-600 hover:underline">
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
    </x-card>

    <livewire:products.update @updated="$refresh" />
</div>
