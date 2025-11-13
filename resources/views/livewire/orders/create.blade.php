<div>
    <x-button :text="__('Create New Order')" wire:click="$toggle('modal')" sm />

    <x-modal :title="__('Create New Order')" wire x-on:open="setTimeout(() => $refs.orderNumber.focus(), 250)" size="lg" blur="xl">
        <form id="order-create" wire:submit="save" class="space-y-4">
            <div>
                <x-input label="{{ __('Order Number') }} *" x-ref="orderNumber" wire:model="order.order_number" required />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <x-select.native
                    label="{{ __('Product') }} *"
                    wire:model="order.product_id"
                    required
                    :options="$products"
                    select="label:name|value:id"
                />

                <x-select.native
                    label="{{ __('User') }} *"
                    wire:model="order.user_id"
                    required
                    :options="$users"
                    select="label:name|value:id"
                />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <x-select.native
                    label="{{ __('Market') }}"
                    wire:model="order.market_id"
                    :options="$markets"
                    select="label:name|value:id"
                />

                <x-number label="{{ __('Total') }} *" wire:model="order.total" min="0" step="0.01" required />
            </div>

            <div>
                <x-select.native
                    label="{{ __('Status') }} *"
                    wire:model="order.status"
                    required
                    :options="[
                        ['label' => 'Processing', 'value' => 'processing'],
                        ['label' => 'Completed', 'value' => 'completed'],
                        ['label' => 'Cancelled', 'value' => 'cancelled'],
                    ]"
                    select="label:label|value:value"
                />
            </div>
        </form>
        <x-slot:footer>
            <x-button type="submit" form="order-create">
                @lang('Save')
            </x-button>
        </x-slot:footer>
    </x-modal>
</div>
