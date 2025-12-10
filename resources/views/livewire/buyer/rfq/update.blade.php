<div>
    <x-modal :title="__('Update RFQ')" wire blur="xl" size="3xl">
        @if($request)
            <form id="buyer-rfq-update" wire:submit="save" class="space-y-4">
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
                                wire:key="buyer-rfq-update-item-{{ $index }}"
                            >
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                                    <div class="md:col-span-5">
                                        <x-input
                                            label="{{ __('Product Name') }} *"
                                            wire:model="items.{{ $index }}.product_name"
                                            list="product-names-datalist-update"
                                            placeholder="{{ __('Type or select product name') }}"
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

                    {{-- Datalist for product name autocomplete suggestions --}}
                    <datalist id="product-names-datalist-update">
                        @foreach($productNames as $productName)
                            <option value="{{ $productName }}">
                        @endforeach
                    </datalist>
                </div>
            </form>
            <x-slot:footer>
                <x-button type="submit" form="buyer-rfq-update">
                    @lang('Update RFQ')
                </x-button>
            </x-slot:footer>
        @endif
    </x-modal>
</div>
