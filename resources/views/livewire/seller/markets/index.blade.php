<div class="space-y-4">
    <x-seller.nav />

    <div>
        <x-card>
            <x-alert color="black" icon="map-pin">
                @lang('My Markets')
            </x-alert>

            <div class="mb-2 mt-4">
                @can('create_markets')
                    <livewire:seller.markets.create @created="$refresh" />
                @endcan
            </div>

            <x-table :$headers :$sort :rows="$this->rows" paginate :paginator="null" filter loading :quantity="[5, 10, 20, 'all']">
                @interact('column_id', $row)
                    {{ $row->id }}
                @endinteract

                @interact('column_name', $row)
                    <a href="{{ route('seller.markets.show', $row) }}" class="text-blue-600 hover:underline">
                        <x-badge text="{{ $row->name }}" icon="building-storefront" position="left" />
                    </a>
                @endinteract

                @interact('column_location', $row)
                    {{ $row->location ?? '-' }}
                @endinteract

                @interact('column_created_at', $row)
                    {{ $row->created_at->diffForHumans() }}
                @endinteract

                @interact('column_products_count', $row)
                    <x-badge :text="$row->products_count" icon="archive-box" />
                @endinteract

                @interact('column_action', $row)
                    <div class="flex gap-1">
                        @can('edit_markets')
                            <x-button.circle icon="pencil" wire:click="$dispatch('seller::load::market', { 'market' : '{{ $row->id }}'})" />
                        @endcan
                        @can('delete_markets')
                            <livewire:seller.markets.delete :market="$row" :key="uniqid('', true)" @deleted="$refresh" />
                        @endcan
                    </div>
                @endinteract
            </x-table>
        </x-card>

        <livewire:seller.markets.update @updated="$refresh" />
    </div>
</div>
