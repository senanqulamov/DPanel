<div>
    <x-card>
        <x-slot:header>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-[var(--color-text-high)]">
                        {{ $request->title }}
                    </h2>
                    <p class="text-sm text-[var(--color-text-muted)]">
                        @lang('RFQ #:id', ['id' => $request->id])
                    </p>
                </div>

                <div class="flex flex-col items-end gap-2">
                    <x-badge :color="$request->status === 'open' ? 'green' : ($request->status === 'closed' ? 'red' : 'gray')">
                        {{ ucfirst($request->status) }}
                    </x-badge>
                    <div class="text-xs text-[var(--color-text-muted)]">
                        @lang('Deadline'):
                        {{ optional($request->deadline)->format('Y-m-d') ?? '—' }}
                    </div>
                </div>
            </div>
        </x-slot:header>

        <div class="space-y-8">
            <div>
                <h3 class="text-md font-semibold text-[var(--color-text-high)] mb-2">
                    @lang('Details')
                </h3>
                <dl class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <dt class="font-medium text-[var(--color-text-muted)]">@lang('Buyer')</dt>
                        <dd class="text-[var(--color-text)]">{{ $request->buyer?->name ?? __('Unknown') }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-[var(--color-text-muted)]">@lang('Created at')</dt>
                        <dd class="text-[var(--color-text)]">{{ $request->created_at->format('Y-m-d H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-[var(--color-text-muted)]">@lang('Deadline')</dt>
                        <dd class="text-[var(--color-text)]">{{ optional($request->deadline)->format('Y-m-d') ?? '—' }}</dd>
                    </div>
                </dl>

                @if($request->description)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-[var(--color-text-muted)] mb-1">@lang('Description')</h4>
                        <p class="text-sm text-[var(--color-text)] whitespace-pre-line">{{ $request->description }}</p>
                    </div>
                @endif
            </div>

            <div>
                <h3 class="text-md font-semibold text-[var(--color-text-high)] mb-2">
                    @lang('Items') ({{ $request->items->count() }})
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[var(--color-border)]">
                        <thead class="bg-[var(--color-surface)]">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-[var(--color-text-muted)] uppercase tracking-wider">@lang('Product')</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-[var(--color-text-muted)] uppercase tracking-wider">@lang('Quantity')</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-[var(--color-text-muted)] uppercase tracking-wider">@lang('Specifications')</th>
                            </tr>
                        </thead>
                        <tbody class="bg-[var(--color-surface-raised)] divide-y divide-[var(--color-border)]">
                            @foreach($request->items as $item)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-[var(--color-text)]">
                                        {{ $item->product?->name ?? __('Unknown product') }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-[var(--color-text)]">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-[var(--color-text)]">
                                        {{ $item->specifications ?: '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-md font-semibold text-[var(--color-text-high)]">
                        @lang('Quotes') ({{ $request->quotes->count() }})
                    </h3>

                    @if($canQuote)
                        <x-button :href="route('rfq.quote', $request)" text="{{ __('Submit Quote') }}" icon="currency-dollar" sm />
                    @endif
                </div>

                @if($request->quotes->isEmpty())
                    <p class="text-sm text-[var(--color-text-muted)]">
                        @lang('No quotes submitted yet.')
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-[var(--color-border)]">
                            <thead class="bg-[var(--color-surface)]">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-[var(--color-text-muted)] uppercase tracking-wider">@lang('Supplier')</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-[var(--color-text-muted)] uppercase tracking-wider">@lang('Unit Price')</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-[var(--color-text-muted)] uppercase tracking-wider">@lang('Total Price')</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-[var(--color-text-muted)] uppercase tracking-wider">@lang('Status')</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-[var(--color-text-muted)] uppercase tracking-wider">@lang('Submitted')</th>
                                </tr>
                            </thead>
                            <tbody class="bg-[var(--color-surface-raised)] divide-y divide-[var(--color-border)]">
                                @foreach($request->quotes as $quote)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-[var(--color-text)]">
                                            {{ $quote->supplier?->name ?? __('Unknown') }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-[var(--color-text)]">
                                            {{ number_format($quote->unit_price, 2) }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-[var(--color-text)]">
                                            {{ $quote->formatted_total_price }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            <x-badge :color="$quote->status === 'submitted' ? 'blue' : 'gray'">
                                                {{ ucfirst($quote->status ?? 'submitted') }}
                                            </x-badge>
                                        </td>
                                        <td class="px-4 py-2 text-sm text-[var(--color-text-muted)] text-right">
                                            {{ $quote->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </x-card>
</div>
