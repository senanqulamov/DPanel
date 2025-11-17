<div>
    <x-button :text="__('Create New Order')" wire:click="$toggle('modal')" sm />

    <x-modal :title="__('Create New Order')" wire x-on:open="setTimeout(() => $refs.orderNumber.focus(), 250)" size="xl" blur="xl">
        <form id="order-create" wire:submit="save" class="space-y-4">
            <div>
                <x-input label="{{ __('Order Number') }} *" x-ref="orderNumber" wire:model="order.order_number" required />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <x-select.styled
                    label="{{ __('User') }} *"
                    wire:model="order.user_id"
                    required
                    :options="$users"
                    select="label:name|value:id"
                    searchable
                />

                <x-select.styled
                    label="{{ __('Market') }}"
                    wire:model="order.market_id"
                    :options="$markets"
                    select="label:name|value:id"
                    searchable
                />
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-semibold">{{ __('Items') }} *</span>
                    <x-button sm icon="plus" wire:click.prevent="addItem"/>
                </div>
                <div class="space-y-2">
                    @foreach($items as $idx => $it)
                        <div class="grid grid-cols-12 gap-2 items-end">
                            <div class="col-span-7">
                                <x-select.styled
                                    label="{{ __('Product') }}"
                                    wire:model="items.{{ $idx }}.product_id"
                                    :options="$products"
                                    select="label:name|value:id"
                                    searchable
                                />
                            </div>
                            <div class="col-span-3">
                                <x-number label="{{ __('Qty') }}" wire:model="items.{{ $idx }}.quantity" min="1" step="1"/>
                            </div>
                            <div class="col-span-2 flex gap-2">
                                <x-button.circle icon="trash" color="red" wire:click.prevent="removeItem({{ $idx }})"/>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <x-select.styled
                    label="{{ __('Status') }} *"
                    wire:model="order.status"
                    required
                    :options="[
                        ['label' => 'Processing', 'value' => 'processing'],
                        ['label' => 'Completed', 'value' => 'completed'],
                        ['label' => 'Cancelled', 'value' => 'cancelled'],
                    ]"
                    select="label:label|value:value"
                    searchable
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
