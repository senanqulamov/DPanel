<div class="space-y-6">
    {{-- Header --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-slate-900 to-slate-800 border border-slate-700/60 shadow-xl">
        <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent"></div>
        <div class="relative p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-slate-700/50 flex items-center justify-center border border-slate-600/60">
                            <x-icon name="tag" class="w-6 h-6 text-slate-100"/>
                        </div>
                        <div class="min-w-0">
                            <h1 class="text-2xl font-bold text-slate-200 truncate">
                                {{ $category->name }}
                            </h1>
                            <div class="text-sm text-slate-300">{{ $category->products()->count() }} {{ __('products') }}</div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    <x-button icon="arrow-left" href="{{ route('categories.index') }}">{{ __('Back to List') }}</x-button>
                    <x-button icon="pencil" wire:click="$dispatch('load::category', { category: '{{ $category->id }}' })">{{ __('Edit Category') }}</x-button>
                </div>
            </div>
        </div>
    </div>

    {{-- Products in this category --}}
    <x-card>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">{{ __('Products in this Category') }}</h2>
        </div>

        @if($this->products->isNotEmpty())
            <x-table
                :$headers
                :$sort
                :rows="$this->products"
                paginate
                :paginator="null"
                filter
                loading
                :quantity="[10, 20, 50, 'all']"
            >
                @interact('column_id', $row)
                    <span class="text-sm text-gray-900 dark:text-gray-100">#{{ $row->id }}</span>
                @endinteract

                @interact('column_name', $row)
                <a href="{{ route('products.show', $row) }}" class="text-blue-600 hover:underline">
                    <x-badge text="{{ $row->name }}" icon="eye" position="left"/>
                </a>
                @endinteract

                @interact('column_sku', $row)
                    <span class="font-mono text-sm text-gray-600 dark:text-gray-300">{{ $row->sku }}</span>
                @endinteract

                @interact('column_market', $row)
                <a href="{{ route('markets.show', $row->market) }}" class="text-blue-600 hover:underline">
                    <x-badge text="{{ $row->market->name ?? '—' }}" icon="building-storefront" position="left"/>
                </a>
                @endinteract

                @interact('column_supplier', $row)
                    @if($row->supplier)
                        <a href="{{ route('users.show', $row->supplier) }}" class="text-blue-600 hover:underline">
                            {{ $row->supplier->name }}
                        </a>
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                @endinteract

                @interact('column_price', $row)
                    <span class="font-semibold text-gray-900 dark:text-gray-100">${{ number_format($row->price, 2) }}</span>
                @endinteract

                @interact('column_stock', $row)
                    <x-badge :text="$row->stock" :color="$row->stock > 0 ? 'green' : 'red'"/>
                @endinteract
            </x-table>
        @else
            <div class="text-center py-8 text-gray-500">
                {{ __('No products in this category yet.') }}
            </div>
        @endif
    </x-card>

    <livewire:categories.update @updated="$refresh"/>
</div>
