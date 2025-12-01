<div>
    <x-card>
        <x-slot:header>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-[var(--color-text-high)]">
                        @lang('Submit Quote for :title', ['title' => $request->title])
                    </h2>
                    <p class="text-sm text-[var(--color-text-muted)]">
                        @lang('Buyer: :name', ['name' => $request->buyer?->name ?? __('Unknown')])
                    </p>
                </div>

                <div class="text-xs text-[var(--color-text-muted)] text-right">
                    <div>
                        @lang('RFQ #:id', ['id' => $request->id])
                    </div>
                    <div>
                        @lang('Deadline'): {{ optional($request->deadline)->format('Y-m-d') ?? '—' }}
                    </div>
                </div>
            </div>
        </x-slot:header>

        <form wire:submit.prevent="save" class="space-y-6">
            <div class="space-y-4">
                @foreach($request->items as $item)
                    <div class="border border-[var(--color-border)] rounded-lg p-4 bg-[var(--color-surface-raised)]" wire:key="quote-item-{{ $item->id }}">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                            <div class="md:col-span-4">
                                <div class="text-sm font-medium text-[var(--color-text-high)]">
                                    {{ $item->product?->name ?? __('Unknown product') }}
                                </div>
                                <div class="text-xs text-[var(--color-text-muted)]">
                                    @lang('Requested quantity'): {{ $item->quantity }}
                                </div>
                                @if($item->specifications)
                                    <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                        {{ $item->specifications }}
                                    </div>
                                @endif
                            </div>

                            <div class="md:col-span-3">
                                <x-input
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    label="{{ __('Unit Price') }}"
                                    wire:model="items.{{ $item->id }}.unit_price"
                                />
                            </div>

                            <div class="md:col-span-3">
                                <x-input
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    label="{{ __('Total (auto-calculated)') }}"
                                    :value="isset($items[$item->id]['unit_price']) ? number_format(($items[$item->id]['unit_price'] ?? 0) * ($items[$item->id]['quantity'] ?? $item->quantity), 2) : ''"
                                    readonly
                                />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div>
                <x-textarea
                    label="{{ __('Notes (optional)') }}"
                    wire:model.defer="notes"
                    rows="3"
                />
            </div>

            <div class="flex justify-end pt-4 border-t mt-4">
                <x-button type="submit">
                    @lang('Submit Quote')
                </x-button>
            </div>
        </form>
    </x-card>
</div>
