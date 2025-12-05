<div class="space-y-6">
    <x-supplier.nav />

    {{-- Modern Header Card --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-600 via-purple-500 to-fuchsia-500 text-white shadow-2xl shadow-purple-500/30">
        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>

        <div class="relative p-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('supplier.rfq.index') }}" class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30 hover:bg-white/30 transition">
                    <x-icon name="arrow-left" class="w-5 h-5 text-white" />
                </a>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold tracking-tight">
                        {{ __('Submit Quote') }}
                    </h1>
                    <p class="text-sm text-purple-100 mt-0.5">
                        {{ $request->title }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="relative overflow-hidden rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-gray-200/50 dark:border-slate-700/50 shadow-xl">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 via-transparent to-fuchsia-500/5 dark:from-purple-500/10 dark:to-fuchsia-500/10"></div>

        <div class="relative p-6">
            <form wire:submit.prevent="submitQuote" class="space-y-6">
                {{-- RFQ Details --}}
                <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4">
                    <h3 class="text-lg font-semibold mb-3">{{ __('RFQ Details') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">{{ __('Buyer') }}:</span>
                            <span class="ml-2 font-medium">{{ $request->buyer?->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">{{ __('Deadline') }}:</span>
                            <span class="ml-2 font-medium">{{ $request->deadline?->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Quote Items --}}
                <div>
                    <h3 class="text-lg font-semibold mb-3">{{ __('Quote Items') }}</h3>
                    <div class="space-y-3">
                        @foreach($quote['items'] as $index => $item)
                            <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium mb-1">{{ __('Product') }}</label>
                                        <input type="text" value="{{ $item['product_name'] }}" disabled class="w-full rounded-lg bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 px-3 py-2 text-sm" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1">{{ __('Quantity') }}</label>
                                        <input type="number" value="{{ $item['quantity'] }}" disabled class="w-full rounded-lg bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 px-3 py-2 text-sm" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1">{{ __('Unit Price') }} *</label>
                                        <input type="number" step="0.01" wire:model.live="quote.items.{{ $index }}.unit_price" required class="w-full rounded-lg border border-gray-300 dark:border-slate-600 px-3 py-2 text-sm focus:border-purple-500 focus:ring-purple-500" />
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium mb-1">{{ __('Notes') }}</label>
                                        <input type="text" wire:model="quote.items.{{ $index }}.notes" class="w-full rounded-lg border border-gray-300 dark:border-slate-600 px-3 py-2 text-sm" />
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium mb-1">{{ __('Item Total') }}</label>
                                        <input type="text" value="${{ number_format($item['total'], 2) }}" disabled class="w-full rounded-lg bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 px-3 py-2 text-sm font-semibold" />
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Quote Details --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Valid Until') }} *</label>
                        <input type="date" wire:model="quote.valid_until" required class="w-full rounded-lg border border-gray-300 dark:border-slate-600 px-3 py-2" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Total Amount') }}</label>
                        <input type="text" value="${{ number_format($quote['total_amount'], 2) }}" disabled class="w-full rounded-lg bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 px-3 py-2 font-bold text-lg" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">{{ __('Notes') }}</label>
                        <textarea wire:model="quote.notes" rows="3" class="w-full rounded-lg border border-gray-300 dark:border-slate-600 px-3 py-2"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">{{ __('Terms & Conditions') }}</label>
                        <textarea wire:model="quote.terms" rows="3" class="w-full rounded-lg border border-gray-300 dark:border-slate-600 px-3 py-2"></textarea>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 justify-end">
                    <x-button text="{{ __('Cancel') }}" color="gray" href="{{ route('supplier.rfq.index') }}" />
                    <x-button text="{{ __('Submit Quote') }}" color="purple" type="submit" icon="paper-airplane" />
                </div>
            </form>
        </div>
    </div>
</div>
