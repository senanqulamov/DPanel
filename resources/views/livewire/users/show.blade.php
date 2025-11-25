<div>
    <x-card>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ $user->name }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                @if($user->company_name)
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $user->company_name }}</p>
                @endif

                <!-- Role Badges -->
                <div class="flex gap-2 mt-2 flex-wrap">
                    @if($user->is_buyer)
                        <x-badge color="blue" text="Buyer" icon="shopping-cart" position="left" />
                    @endif
                    @if($user->is_seller)
                        <x-badge color="green" text="{{ $user->verified_seller ? 'Verified Seller' : 'Seller' }}" icon="building-storefront" position="left" />
                    @endif
                    @if($user->is_supplier)
                        @if($user->supplier_status === 'active')
                            <x-badge color="purple" text="Active Supplier" icon="cube" position="left" />
                        @else
                            <x-badge color="slate" text="Supplier ({{ ucfirst($user->supplier_status) }})" icon="cube" position="left" />
                        @endif
                    @endif
                </div>
            </div>

            <div class="flex gap-2">
                <x-button icon="arrow-left" href="{{ route('users.index') }}">
                    @lang('Back to Users')
                </x-button>
                <x-button icon="pencil" wire:click="$dispatch('load::user', { user: '{{ $user->id }}' })">
                    @lang('Edit User')
                </x-button>
            </div>
        </div>

        <!-- Business Information Section -->
        @if($user->is_supplier || $user->is_seller || $user->company_name)
        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <h3 class="text-md font-semibold mb-3 text-gray-900 dark:text-gray-100">@lang('Business Information')</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                @if($user->company_name)
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Company:</span>
                        <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $user->company_name }}</span>
                    </div>
                @endif
                @if($user->tax_id)
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Tax ID:</span>
                        <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $user->tax_id }}</span>
                    </div>
                @endif
                @if($user->phone)
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Phone:</span>
                        <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $user->phone }}</span>
                    </div>
                @endif
                @if($user->supplier_code)
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Supplier Code:</span>
                        <span class="ml-2 text-gray-900 dark:text-gray-100 font-mono">{{ $user->supplier_code }}</span>
                    </div>
                @endif
                @if($user->ariba_network_id)
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Ariba Network ID:</span>
                        <span class="ml-2 text-gray-900 dark:text-gray-100 font-mono">{{ $user->ariba_network_id }}</span>
                    </div>
                @endif
                @if($user->rating)
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Rating:</span>
                        <span class="ml-2 text-gray-900 dark:text-gray-100">⭐ {{ number_format($user->rating, 2) }}/5.00</span>
                    </div>
                @endif
            </div>
            @if($user->hasCompleteAddress())
                <div class="mt-3">
                    <span class="text-gray-500 dark:text-gray-400">Address:</span>
                    <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $user->getFullAddress() }}</span>
                </div>
            @endif
        </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
            <x-stat :label="__('Orders')" :value="$this->metrics['orders_count']" icon="shopping-bag" />
            <x-stat :label="__('Lifetime Value')" :value="'$' . number_format($this->metrics['lifetime_value'], 2)" icon="banknotes" />
            <x-stat :label="__('Avg Order')" :value="'$' . number_format($this->metrics['avg_order_value'], 2)" icon="chart-bar" />
            <x-stat :label="__('Markets Served')" :value="$this->metrics['markets_count']" icon="building-storefront" />
        </div>

        <div class="mt-8">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">@lang('Markets Served')</h2>
            @if($this->marketsServed->isNotEmpty())
                <div class="flex flex-wrap gap-2">
                    @foreach($this->marketsServed as $market)
                       <a href="{{ route('markets.show', $market) }}">
                           <x-badge :text="$market->name" icon="building-storefront" position="left" />
                       </a>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400">@lang('No markets associated with this user yet.')</p>
            @endif
        </div>

        @if($user->is_supplier && $this->suppliedProducts->isNotEmpty())
        <div class="mt-8">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">@lang('Products Supplied')</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($this->suppliedProducts as $product)
                    <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:underline font-semibold">
                            {{ $product->name }}
                        </a>
                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            <div>SKU: <span class="font-mono">{{ $product->sku }}</span></div>
                            <div>Price: <span class="font-semibold">${{ number_format($product->price, 2) }}</span></div>
                            <div>Stock: {{ $product->stock }}</div>
                            @if($product->market)
                                <div class="mt-1">
                                    <x-badge :text="$product->market->name" color="slate" sm />
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="mt-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">@lang('Recent Orders')</h2>
            </div>

            <x-table :headers="[
                ['index' => 'order_number', 'label' => __('Order Number')],
                ['index' => 'total', 'label' => __('Total')],
                ['index' => 'status', 'label' => __('Status')],
                ['index' => 'created_at', 'label' => __('Created')],
            ]" :rows="$this->orders" paginate :paginator="null" loading>
                @interact('column_order_number', $row)
                <a href="{{ route('orders.show', $row) }}" class="text-blue-600 hover:underline">
                    <x-badge text="{{ $row->order_number }}" icon="queue-list" position="left" />
                </a>
                @endinteract

                @interact('column_total', $row)
                ${{ number_format($row->total, 2) }}
                @endinteract

                @interact('column_status', $row)
                <x-badge :text="ucfirst($row->status)" />
                @endinteract

                @interact('column_created_at', $row)
                {{ $row->created_at->diffForHumans() }}
                @endinteract
            </x-table>
        </div>
    </x-card>

    <livewire:users.update @updated="$refresh" />
</div>
