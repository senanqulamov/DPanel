<div class="space-y-6">
    <x-supplier.nav />

    {{-- Modern Header Card --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-600 via-emerald-500 to-green-500 text-white shadow-2xl shadow-emerald-500/30">
        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>

        <div class="relative p-6">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('supplier.orders.index') }}" class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30 hover:bg-white/30 transition">
                        <x-icon name="arrow-left" class="w-5 h-5 text-white" />
                    </a>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold tracking-tight">
                            {{ __('Create New Order') }}
                        </h1>
                        <p class="text-sm text-emerald-100 mt-0.5">
                            {{ __('Select products from markets to place an order') }}
                        </p>
                    </div>
                </div>

                @if(count($cart) > 0)
                    <div class="px-4 py-2 rounded-xl bg-white/20 backdrop-blur-sm border border-white/30">
                        <div class="text-xs text-emerald-100">{{ __('Cart Total') }}</div>
                        <div class="text-2xl font-bold">${{ number_format($this->total, 2) }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content - Markets & Products --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Market Selection --}}
            <div class="relative overflow-hidden rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-gray-200/50 dark:border-slate-700/50 shadow-xl p-6">
                <h2 class="text-xl font-bold mb-4">{{ __('Select Market') }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($this->markets as $market)
                        <div
                            wire:click="selectMarket({{ $market->id }})"
                            class="p-4 rounded-xl border-2 cursor-pointer transition-all hover:shadow-lg
                                {{ $selectedMarketId === $market->id
                                    ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20'
                                    : 'border-gray-200 dark:border-slate-700 hover:border-emerald-300' }}"
                        >
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $market->name }}</h3>
                                @if($selectedMarketId === $market->id)
                                    <x-icon name="check-circle" class="w-5 h-5 text-emerald-600" />
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                <x-icon name="user" class="w-4 h-4 inline" /> {{ $market->seller?->name ?? __('Unknown Seller') }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $market->products->count() }} {{ __('products available') }}</p>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-8 text-gray-500">
                            {{ __('No markets with available products') }}
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Available Products --}}
            @if($this->selectedMarket)
                <div class="relative overflow-hidden rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-gray-200/50 dark:border-slate-700/50 shadow-xl p-6">
                    <h2 class="text-xl font-bold mb-4">{{ __('Available Products') }}</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($this->selectedMarket->products as $product)
                            <div class="p-4 rounded-xl border border-gray-200 dark:border-slate-700 hover:border-emerald-500 transition-all">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $product->name }}</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('SKU') }}: {{ $product->sku }}</p>
                                    </div>
                                    <span class="font-bold text-lg text-emerald-600">${{ number_format($product->price, 2) }}</span>
                                </div>

                                <div class="flex items-center justify-between text-sm mb-3">
                                    <span class="text-gray-600 dark:text-gray-400">{{ __('Stock') }}:</span>
                                    <x-badge
                                        :text="$product->stock . ' ' . __('units')"
                                        :color="$product->stock > 10 ? 'green' : ($product->stock > 0 ? 'yellow' : 'red')"
                                        sm
                                    />
                                </div>

                                <x-button
                                    color="emerald"
                                    icon="plus"
                                    wire:click="addToCart({{ $product->id }})"
                                    class="w-full justify-center"
                                    sm
                                    :disabled="$product->stock < 1"
                                >
                                    {{ __('Add to Cart') }}
                                </x-button>
                            </div>
                        @empty
                            <div class="col-span-2 text-center py-8 text-gray-500">
                                {{ __('No products available in this market') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>

        {{-- Cart Sidebar --}}
        <div class="space-y-6">
            <div class="relative overflow-hidden rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-gray-200/50 dark:border-slate-700/50 shadow-xl p-6 sticky top-6">
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <x-icon name="shopping-cart" class="w-5 h-5 text-emerald-600" />
                    {{ __('Shopping Cart') }} ({{ count($cart) }})
                </h3>

                @if(count($cart) > 0)
                    <div class="space-y-3 mb-4 max-h-96 overflow-y-auto">
                        @foreach($cart as $productId => $item)
                            <div class="p-3 rounded-lg bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-sm">{{ $item['name'] }}</h4>
                                    <button
                                        wire:click="removeFromCart({{ $productId }})"
                                        class="text-red-500 hover:text-red-700"
                                    >
                                        <x-icon name="x-mark" class="w-4 h-4" />
                                    </button>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <button
                                            wire:click="updateQuantity({{ $productId }}, {{ $item['quantity'] - 1 }})"
                                            class="w-6 h-6 rounded bg-gray-200 dark:bg-slate-700 hover:bg-gray-300 flex items-center justify-center"
                                        >
                                            <x-icon name="minus" class="w-3 h-3" />
                                        </button>
                                        <input
                                            type="number"
                                            wire:model.blur="cart.{{ $productId }}.quantity"
                                            wire:change="updateQuantity({{ $productId }}, $event.target.value)"
                                            min="1"
                                            max="{{ $item['max_stock'] }}"
                                            class="w-12 text-center border border-gray-300 dark:border-slate-600 rounded px-2 py-1 text-sm"
                                        />
                                        <button
                                            wire:click="updateQuantity({{ $productId }}, {{ $item['quantity'] + 1 }})"
                                            class="w-6 h-6 rounded bg-gray-200 dark:bg-slate-700 hover:bg-gray-300 flex items-center justify-center"
                                            {{ $item['quantity'] >= $item['max_stock'] ? 'disabled' : '' }}
                                        >
                                            <x-icon name="plus" class="w-3 h-3" />
                                        </button>
                                    </div>
                                    <span class="font-semibold text-emerald-600">
                                        ${{ number_format($item['unit_price'] * $item['quantity'], 2) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-200 dark:border-slate-700 pt-4 mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600 dark:text-gray-400">{{ __('Subtotal') }}:</span>
                            <span class="font-semibold">${{ number_format($this->total, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-lg font-bold">
                            <span>{{ __('Total') }}:</span>
                            <span class="text-emerald-600">${{ number_format($this->total, 2) }}</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Order Notes') }} ({{ __('Optional') }})
                        </label>
                        <textarea
                            wire:model="notes"
                            rows="3"
                            class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-800 text-sm"
                            placeholder="{{ __('Add any special instructions or notes...') }}"
                        ></textarea>
                    </div>

                    <x-button
                        color="emerald"
                        icon="check"
                        wire:click="placeOrder"
                        wire:confirm="{{ __('Are you sure you want to place this order?') }}"
                        class="w-full justify-center"
                    >
                        {{ __('Place Order') }}
                    </x-button>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <x-icon name="shopping-cart" class="w-12 h-12 mx-auto mb-2 text-gray-400" />
                        <p>{{ __('Your cart is empty') }}</p>
                        <p class="text-sm">{{ __('Select a market and add products') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
