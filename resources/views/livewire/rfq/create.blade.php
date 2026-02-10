<div>
    <x-button :text="__('Create New Request')" wire:click="$toggle('modal')" sm />

    <x-modal :title="__('Create New Request')" wire x-on:open="setTimeout(() => $refs.title?.focus(), 250)" size="3xl" blur="xl">
        <form id="rfq-create" wire:submit="save" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input
                    x-ref="title"
                    label="{{ __('Title') }} *"
                    wire:model.defer="request.title"
                    required
                />

                <x-date
                    label="{{ __('Deadline') }} *"
                    wire:model.defer="request.deadline"
                    required
                />
            </div>

            @if($isAdmin)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-select.styled
                        label="{{ __('Buyer (Customer)') }} *"
                        wire:model.defer="request.buyer_id"
                        :options="$buyers"
                        select="label:name|value:id"
                        searchable
                        required
                    />

                    <x-select.styled
                        label="{{ __('Request Type') }}"
                        wire:model.defer="request.request_type"
                        :options="[
                            ['label' => __('Internal'), 'value' => 'internal'],
                            ['label' => __('Public Tender'), 'value' => 'public']
                        ]"
                        select="label:label|value:value"
                    />
                </div>
            @endif

            <div>
                <x-textarea
                    label="{{ __('Description') }}"
                    wire:model.defer="request.description"
                    rows="4"
                />
            </div>

            {{-- Delivery Information --}}
            <div class="border-t pt-4">
                <h3 class="text-md font-semibold text-[var(--color-text-high)] mb-4">
                    @lang('Delivery Information')
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input
                        label="{{ __('Delivery Location') }}"
                        wire:model.defer="request.delivery_location"
                        placeholder="{{ __('e.g., Warehouse A, Office Building') }}"
                    />

                    <x-textarea
                        label="{{ __('Delivery Address') }}"
                        wire:model.defer="request.delivery_address"
                        rows="2"
                        placeholder="{{ __('Full delivery address') }}"
                    />
                </div>

                <div class="mt-4">
                    <x-textarea
                        label="{{ __('Special Instructions') }}"
                        wire:model.defer="request.special_instructions"
                        rows="3"
                        placeholder="{{ __('Any special delivery instructions or requirements') }}"
                    />
                </div>
            </div>

            <div class="border-t pt-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-md font-semibold text-[var(--color-text-high)]">
                        @lang('Items') *
                    </h3>
                    <x-button
                        type="button"
                        wire:click="addItem"
                        text="{{ __('Add Item') }}"
                        icon="plus"
                        sm
                    />
                </div>

                <div class="space-y-4">
                    @foreach($items as $index => $item)
                        <div
                            class="border border-[var(--color-border)] rounded-lg p-4 bg-[var(--color-surface-raised)]"
                            wire:key="rfq-item-{{ $index }}"
                        >
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                                <div class="md:col-span-5">
                                    <x-select.styled
                                        label="{{ __('Product') }} *"
                                        wire:model="items.{{ $index }}.product_id"
                                        :options="$products"
                                        select="label:name|value:id"
                                        searchable
                                        required
                                    />
                                </div>

                                <div class="md:col-span-3">
                                    <x-number
                                        label="{{ __('Quantity') }} *"
                                        wire:model="items.{{ $index }}.quantity"
                                        min="1"
                                        required
                                    />
                                </div>

                                <div class="md:col-span-3">
                                    <x-input
                                        label="{{ __('Specifications / Notes') }}"
                                        wire:model="items.{{ $index }}.specifications"
                                    />
                                </div>

                                <div class="md:col-span-1 flex justify-end">
                                    @if(count($items) > 1)
                                        <x-button.circle
                                            type="button"
                                            wire:click="removeItem({{ $index }})"
                                            icon="trash"
                                            color="red"
                                            xs
                                        />
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </form>
        <x-slot:footer>
            <x-button type="submit" form="rfq-create">
                @lang('Save')
            </x-button>
        </x-slot:footer>
    </x-modal>
</div>
