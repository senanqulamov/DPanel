<div>
    <x-modal :title="__('Create New Product')" wire x-on:open="setTimeout(() => $refs.name.focus(), 250)" size="2xl" blur="xl" x-on:products::create::open.window="show = true">
        <form id="product-create" wire:submit="save" class="space-y-4">
            <div>
                <x-input label="{{ __('Name') }} *" x-ref="name" wire:model="product.name" required />
            </div>

            <div>
                <x-input label="{{ __('SKU') }} *" wire:model="product.sku" required />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <x-number label="{{ __('Price') }} *" wire:model="product.price" min="0" step="0.01" required />
                <x-number label="{{ __('Stock') }} *" wire:model="product.stock" min="0" required />
            </div>

            <div>
                <x-select.styled label="{{ __('Category') }} *" wire:model="product.category_id" :options="$categories" select="label:name|value:id" searchable required />
            </div>

            <div>
                <x-select.styled label="{{ __('Market') }} *" wire:model="product.market_id" :options="$markets" select="label:name|value:id" searchable required />
            </div>

            <!-- Product Attributes Section -->
            <div class="border-t pt-4 mt-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                        {{ __('Product Attributes') }}
                    </h3>
                    <button type="button" wire:click="addAttribute" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('Add Attribute') }}
                    </button>
                </div>

                @if(count($productAttributes) > 0)
                    <div class="space-y-2">
                        @foreach($productAttributes as $index => $attribute)
                            <div class="flex gap-2 items-start p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700">
                                <div class="flex-1 grid grid-cols-2 gap-2">
                                    <x-input
                                        placeholder="{{ __('Attribute Name') }}"
                                        wire:model="productAttributes.{{ $index }}.name"
                                    />
                                    <x-input
                                        placeholder="{{ __('Attribute Value') }}"
                                        wire:model="productAttributes.{{ $index }}.value"
                                    />
                                </div>
                                <button type="button" wire:click="removeAttribute({{ $index }})" class="flex-shrink-0 p-2 text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">
                        {{ __('No attributes added yet. Click "Add Attribute" to add product specifications.') }}
                    </p>
                @endif
            </div>
        </form>
        <x-slot:footer>
            <x-button type="submit" form="product-create">
                @lang('Save')
            </x-button>
        </x-slot:footer>
    </x-modal>
</div>
