<div>
    <x-button :text="__('Create New Order')" wire:click="$toggle('modal')" sm />

    <x-modal :title="__('Create New Order')" wire x-on:open="setTimeout(() => $refs.orderNumber.focus(), 250)" size="3xl" blur="xl">
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
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-semibold">{{ __('Add Products') }}</span>
                    <x-button icon="plus" sm type="button" wire:click="addPickerLine">{{ __('Add Line') }}</x-button>
                </div>
                <div class="space-y-2">
                    @foreach($pickers as $pidx => $picker)
                        @php($marketId = (int) data_get($pickers, $pidx.'.market_id'))
                        @php($marketProducts = $marketId ? $products->where('market_id', $marketId)->values() : collect())
                        <div class="grid grid-cols-12 gap-2 items-end" wire:key="picker-{{ $pidx }}">
                            <div class="col-span-3">
                                <x-select.styled
                                    label="{{ __('Market') }}"
                                    :options="$markets"
                                    select="label:name|value:id"
                                    wire:model.live="pickers.{{ $pidx }}.market_id"
                                    searchable
                                />
                            </div>
                            <div class="col-span-7">
                                <x-select.styled
                                    label="{{ __('Products') }}"
                                    :options="$marketProducts"
                                    select="label:name|value:id"
                                    wire:model="pickers.{{ $pidx }}.product_ids"
                                    searchable
                                    multiple
                                    :disabled="!$marketId"
                                />
                            </div>
                            <div class="col-span-2 flex gap-2">
                                <x-button class="w-full" icon="plus" type="button" wire:click="addPickerProducts({{ $pidx }})">{{ __('Add') }}</x-button>
                                <x-button.circle icon="trash" color="red" type="button" wire:click="removePickerLine({{ $pidx }})" />
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-semibold">{{ __('Order Items') }}</span>
                </div>
                <div class="space-y-2">
                    @forelse($items as $idx => $it)
                        <div class="grid grid-cols-12 gap-2 items-end">
                            <div class="col-span-3">
                                @php($market = $markets->firstWhere('id', $it['market_id']))
                                <x-input readonly label="{{ __('Market') }}" :value="$market?->name ?? '-'" />
                            </div>
                            <div class="col-span-5">
                                @php($product = $products->firstWhere('id', $it['product_id']))
                                <x-input readonly label="{{ __('Product') }}" :value="$product?->name ?? '-'" />
                            </div>
                            <div class="col-span-2">
                                <x-number label="{{ __('Quantity') }}" wire:model="items.{{ $idx }}.quantity" min="1" step="1"/>
                            </div>
                            <div class="col-span-2 flex gap-2">
                                <x-button.circle icon="trash" color="red" wire:click.prevent="removeItem({{ $idx }})"/>
                            </div>
                        </div>
                    @empty
                        <x-alert color="gray" flat>{{ __('No items found') }}</x-alert>
                    @endforelse
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
