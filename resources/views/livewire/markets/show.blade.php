<div>
    <x-card>
        <x-alert color="black" icon="map-pin">
            {{ $market->name }}
        </x-alert>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
            <x-stat label="{{__('Orders')}}" :value="$this->metrics['orders_count']" icon="shopping-bag" />
            <x-stat label="{{__('Revenue')}}" :value="'$'.number_format($this->metrics['revenue'], 2)" icon="banknotes" />
            <x-stat label="{{__('Avg Order')}}" :value="'$'.number_format($this->metrics['avg_order_value'], 2)" icon="chart-bar" />
            <x-stat label="{{__('Products')}}" :value="$this->metrics['products_count']" icon="archive-box" />
        </div>

        <div class="mt-6 flex gap-2">
            <x-button icon="arrow-left" href="{{ route('markets.index') }}">@lang('Markets')</x-button>
            <x-button icon="pencil" wire:click="$dispatch('load::market', { market: '{{ $market->id }}'})">{{ __('Update Market: #:id', ['id' => $market->id]) }}</x-button>
        </div>

        <div class="mt-8">
            <h2 class="text-sm font-semibold mb-2">@lang('Recent Orders')</h2>
            <x-table :headers="[['index'=>'order_number','label'=>__('Or   der Number')],['index'=>'total','label'=>__('Total')],['index'=>'status','label'=>__('Status')],['index'=>'created_at','label'=>__('Created')]]" :rows="$this->orders" :sort="['column'=>'created_at','direction'=>'desc']" paginate :paginator="null" loading>
                @interact('column_order_number', $row)
                {{ $row->order_number }}
                @endinteract

                @interact('column_total', $row)
                ${{ number_format($row->total,2) }}
                @endinteract

                @interact('column_status', $row)
                <x-badge :text="ucfirst($row->status)" />
                @endinteract

                @interact('column_created_at', $row)
                {{ $row->created_at->diffForHumans() }}
                @endinteract
            </x-table>
        </div>

        <div class="mt-8">
            <h2 class="text-sm font-semibold mb-2">@lang('Products')</h2>
            <x-table :headers="[['index'=>'name','label'=>__('Name')],['index'=>'sku','label'=>__('SKU')],['index'=>'price','label'=>__('Price')],['index'=>'stock','label'=>__('Stock')],['index'=>'created_at','label'=>__('Created')]]" :rows="$this->products" :sort="['column'=>'created_at','direction'=>'desc']" paginate :paginator="null" loading>
                @interact('column_price', $row)
                ${{ number_format($row->price,2) }}
                @endinteract

                @interact('column_stock', $row)
                <x-badge :text="$row->stock" :color="$row->stock > 0 ? 'green' : 'red'" />
                @endinteract

                @interact('column_created_at', $row)
                {{ $row->created_at->diffForHumans() }}
                @endinteract
            </x-table>
        </div>
    </x-card>

    <livewire:markets.update @updated="$refresh" />
</div>
