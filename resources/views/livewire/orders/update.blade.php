<div>
    <x-slide wire="modal" left size="xl" blur="md">
        <x-slot name="title">{{ __('Update Order: #:id', ['id' => $order?->id]) }}</x-slot>
        <form id="order-update-{{ $order?->id }}" wire:submit="save" class="space-y-6">

            <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
                <x-input
                    label="{{ __('Order Number') }}"
                    wire:model.blur="order.order_number"
                    required
                    hint="{{ __('Unique order identifier') }}"
                />

                <x-select.styled
                    label="{{ __('Product') }}"
                    wire:model="order.product_id"
                    required
                    :options="$products"
                    select="label:name|value:id"
                    hint="{{ __('Select product') }}"
                    searchable
                />
            </div>

            <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
                <x-select.styled
                    label="{{ __('User') }}"
                    wire:model="order.user_id"
                    required
                    :options="$users"
                    select="label:name|value:id"
                    hint="{{ __('Select user') }}"
                    searchable
                />

                <x-select.styled
                    label="{{ __('Market') }}"
                    wire:model="order.market_id"
                    :options="$markets"
                    select="label:name|value:id"
                    hint="{{ __('Optional market') }}"
                    searchable
                />
            </div>

            <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
                <x-number
                    label="{{ __('Total') }}"
                    wire:model.blur="order.total"
                    min="0"
                    step="0.01"
                    required
                    hint="{{ __('Order total amount') }}"
                />

                <x-select.styled
                    label="{{ __('Status') }}"
                    wire:model="order.status"
                    required
                    :options="[
                        ['label' => 'Processing', 'value' => 'processing'],
                        ['label' => 'Completed', 'value' => 'completed'],
                        ['label' => 'Cancelled', 'value' => 'cancelled'],
                    ]"
                    select="label:label|value:value"
                    hint="{{ __('Order status') }}"
                />
            </div>

            <x-button
                type="submit"
                form="order-update-{{ $order?->id }}"
                color="primary"
                loading="save"
                icon="check"
            >
                {{ __('Save Changes') }}
            </x-button>
        </form>
    </x-slide>
</div>
