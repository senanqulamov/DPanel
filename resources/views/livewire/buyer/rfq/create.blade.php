<div>
    <x-modal :title="__('Create New RFQ')" wire blur="xl" size="3xl">
        <form id="buyer-rfq-create" wire:submit="save" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input
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

            <div>
                <x-textarea
                    label="{{ __('Description') }}"
                    wire:model.defer="request.description"
                    rows="4"
                />
            </div>

            <div class="border-t pt-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100">
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
                            class="border border-gray-300 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-800"
                            wire:key="buyer-rfq-item-{{ $index }}"
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

            <div class="border-t pt-4">
                <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-3">
                    @lang('Invite Suppliers') ({{ __('Optional') }})
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    @lang('Select suppliers to invite for this RFQ. They will receive a notification.')
                </p>

                <div>
                    <x-select.styled
                        label="{{ __('Select Suppliers') }}"
                        wire:model="selectedSuppliers"
                        :options="$suppliers"
                        select="label:name|value:id"
                        searchable
                        multiple
                    />
                </div>

                @if(!empty($selectedSuppliers))
                    <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                        <strong>{{ count($selectedSuppliers) }}</strong> {{ __('supplier(s) selected') }}
                    </div>
                @endif
            </div>
        </form>
        <x-slot:footer>
            <x-button type="submit" form="buyer-rfq-create">
                @lang('Create RFQ')
            </x-button>
        </x-slot:footer>
    </x-modal>
</div>
