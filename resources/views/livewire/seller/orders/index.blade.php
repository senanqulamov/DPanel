<div class="space-y-4">
    <x-seller.nav />

    <x-card>
        <x-alert color="black" icon="shopping-cart">
            @lang('My Orders')
        </x-alert>

        <x-table :$headers :$sort :rows="$this->rows" paginate :paginator="null" filter loading :quantity="[5, 10, 20, 'all']">
            @interact('column_id', $row)
                {{ $row->id }}
            @endinteract

            @interact('column_order_number', $row)
                <a href="{{ route('seller.orders.show', $row) }}" class="text-blue-600 hover:underline">
                    <x-badge text="{{ $row->order_number }}" icon="document-text" position="left" />
                </a>
            @endinteract

            @interact('column_markets', $row)
                @php
                    $markets = $row->items->pluck('market')->unique('id')->filter();
                @endphp
                @if($markets->count() > 0)
                    <div class="flex flex-wrap gap-1">
                        @foreach($markets as $market)
                            <x-badge text="{{ $market->name }}" icon="building-storefront" position="left" xs />
                        @endforeach
                    </div>
                @else
                    <span class="text-gray-400">-</span>
                @endif
            @endinteract

            @interact('column_total', $row)
                <span class="font-semibold">${{ number_format($row->total, 2) }}</span>
            @endinteract

            @interact('column_status', $row)
                <x-badge
                    :text="ucfirst($row->status)"
                    :color="match($row->status) {
                        'processing' => 'blue',
                        'completed' => 'green',
                        'cancelled' => 'red',
                        default => 'gray'
                    }"
                />
            @endinteract

            @interact('column_created_at', $row)
                {{ $row->created_at->diffForHumans() }}
            @endinteract

            @interact('column_action', $row)
                <div class="flex gap-1">
                    <x-button.circle icon="eye" color="primary" wire:click="$dispatch('view::order', { 'order' : '{{ $row->id }}'})" />
                </div>
            @endinteract
        </x-table>
    </x-card>

    <livewire:orders.view-order />
</div>
