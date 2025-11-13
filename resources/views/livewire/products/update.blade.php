<div>
    <x-slide wire="modal" bottom size="xl" blur="md">
        <x-slot name="title">{{ __('Update Product: #:id', ['id' => $product?->id]) }}</x-slot>
        <form id="product-update-{{ $product?->id }}" wire:submit="save" class="space-y-6">

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <x-input
                    label="{{ __('Name') }}"
                    wire:model.blur="product.name"
                    required
                    hint="{{ __('Product name') }}"
                />

                <x-input
                    label="{{ __('SKU') }}"
                    wire:model.blur="product.sku"
                    required
                    hint="{{ __('Stock Keeping Unit') }}"
                />

                <x-number
                    label="{{ __('Price') }}"
                    wire:model.blur="product.price"
                    min="0"
                    step="0.01"
                    required
                    hint="{{ __('Product price') }}"
                />

                <x-number
                    label="{{ __('Stock') }}"
                    wire:model.blur="product.stock"
                    min="0"
                    required
                    hint="{{ __('Available quantity') }}"
                />
            </div>

            <div>
                <x-input
                    label="{{ __('Category') }}"
                    wire:model.blur="product.category"
                    hint="{{ __('Product category') }}"
                />
            </div>

            <x-button
                type="submit"
                form="product-update-{{ $product?->id }}"
                color="primary"
                loading="save"
                icon="check"
            >
                {{ __('Save Changes') }}
            </x-button>
        </form>
    </x-slide>
</div>
