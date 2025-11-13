<div>
    <x-card>
        <x-alert color="black" icon="shopping-bag">
            @lang('Orders')
        </x-alert>

        <div class="mb-2 mt-4">
            <livewire:orders.create @created="$refresh"/>
        </div>

        <x-table :$headers :$sort :rows="$this->rows" paginate :paginator="null" filter loading :quantity="[5, 10, 20, 'all']">
            @interact('column_product', $row)
            {{ $row->product->name ?? '-' }}
            @endinteract

            @interact('column_user', $row)
            {{ $row->user->name ?? '-' }}
            @endinteract

            @interact('column_market', $row)
            {{ $row->market->name ?? '-' }}
            @endinteract

            @interact('column_total', $row)
            ${{ number_format($row->total, 2) }}
            @endinteract

            @interact('column_status', $row)
            @php
                $color = match($row->status) {
                    'processing' => 'blue',
                    'completed' => 'green',
                    'cancelled' => 'red',
                    default => 'gray'
                };
            @endphp
            <x-badge :text="ucfirst($row->status)" :color="$color" />
            @endinteract

            @interact('column_created_at', $row)
            {{ $row->created_at->diffForHumans() }}
            @endinteract

            @interact('column_action', $row)
            <div class="flex gap-1">
                <x-button.circle icon="pencil" wire:click="$dispatch('load::order', { 'order' : '{{ $row->id }}'})"/>
                <livewire:orders.delete :order="$row" :key="uniqid('', true)" @deleted="$refresh"/>
            </div>
            @endinteract
        </x-table>
    </x-card>

    <livewire:orders.update @updated="$refresh"/>
</div>
