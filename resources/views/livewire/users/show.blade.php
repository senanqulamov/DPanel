<div>
    <x-card>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ $user->name }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
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
                        <x-badge :text="$market->name" icon="building-storefront" position="left" />
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400">@lang('No markets associated with this user yet.')</p>
            @endif
        </div>

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
                    {{ $row->order_number }}
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
