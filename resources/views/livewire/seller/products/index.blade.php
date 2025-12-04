<div class="space-y-6">
    <x-seller.nav />

    {{-- Modern Header Card - 2026 Style --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-600 via-purple-500 to-fuchsia-500 text-white shadow-2xl shadow-purple-500/30">
        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>

        <div class="relative p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30">
                        <x-icon name="shopping-cart" class="w-7 h-7 text-white" />
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold tracking-tight">
                            @lang('My Products')
                        </h1>
                        <p class="text-sm text-purple-100 mt-0.5">
                            {{ __('Manage your product catalog and inventory') }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="px-4 py-2 rounded-xl bg-white/20 backdrop-blur-sm border border-white/30">
                        <div class="text-xs text-purple-100">{{ __('Total Products') }}</div>
                        <div class="text-2xl font-bold">{{ $this->rows->total() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="relative overflow-hidden rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-gray-200/50 dark:border-slate-700/50 shadow-xl">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 via-transparent to-fuchsia-500/5 dark:from-purple-500/10 dark:to-fuchsia-500/10"></div>

        <div class="relative p-6">
            <div class="mb-6">
                <livewire:products.create @created="$refresh" />
            </div>

            <x-table :$headers :$sort :rows="$this->rows" paginate :paginator="null" filter loading :quantity="[5, 10, 20, 'all']">
            @interact('column_name', $row)
                <a href="{{ route('seller.products.show', $row) }}" class="text-blue-600 hover:underline">
                    <x-badge text="{{ $row->name }}" icon="eye" position="left" />
                </a>
            @endinteract

            @interact('column_price', $row)
                ${{ number_format($row->price, 2) }}
            @endinteract

            @interact('column_stock', $row)
                <x-badge :text="$row->stock" :color="$row->stock > 0 ? 'green' : 'red'" />
            @endinteract

            @interact('column_market', $row)
                @if($row->market)
                    <x-badge text="{{ $row->market->name }}" icon="building-storefront" position="left" />
                @else
                    -
                @endif
            @endinteract

            @interact('column_created_at', $row)
                {{ $row->created_at->diffForHumans() }}
            @endinteract

            @interact('column_action', $row)
                <div class="flex gap-1">
                    @can('edit_products')
                        <x-button.circle icon="pencil" wire:click="$dispatch('load::product', { 'product' : '{{ $row->id }}'})" />
                    @endcan
                    @can('delete_products')
                        <livewire:products.delete :product="$row" :key="uniqid('', true)" @deleted="$refresh" />
                    @endcan
                </div>
            @endinteract
        </x-table>
        </div>
    </div>

    <livewire:products.update @updated="$refresh" />
</div>
