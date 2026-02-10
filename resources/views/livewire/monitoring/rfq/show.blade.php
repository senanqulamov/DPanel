<div class="space-y-6">

    {{-- Modern Header Card --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 via-blue-500 to-cyan-500 text-white shadow-2xl shadow-blue-500/30">
        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>

        <div class="relative p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30">
                        <x-icon name="document-text" class="w-7 h-7 text-white"/>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold tracking-tight">
                            {{ $request->title }}
                        </h1>
                        <p class="text-sm text-blue-100 mt-0.5">
                            @lang('RFQ #:id', ['id' => $request->id])
                        </p>
                    </div>
                </div>

                <div class="flex flex-col items-end gap-2">
                    <x-badge
                        :text="ucfirst(__($request->status))"
                        :color="match($request->status) {
                            'open' => 'green',
                            'closed' => 'red',
                            default => 'gray'
                        }"
                        class="text-white"
                    />
                    <div class="text-xs text-blue-100">
                        @lang('Deadline'): {{ optional($request->deadline)->format('Y-m-d') ?? '—' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions Card --}}
    <div class="flex flex-wrap gap-3">
        {{-- Export Dropdown --}}
        <div x-data="{ open: false }" @click.away="open = false" class="relative">
            <x-button
                icon="arrow-down-tray"
                color="secondary"
                @click="open = !open"
            >
                {{ __('Export') }}
            </x-button>

            <div
                x-show="open"
                x-transition
                class="absolute left-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50"
                style="display: none;"
            >
                <div class="py-1">
                    <a
                        href="{{ route('export.rfq.pdf', $request) }}"
                        target="_blank"
                        class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                        @click="open = false"
                    >
                        <x-icon name="document-text" class="w-4 h-4" />
                        {{ __('RFQ as PDF') }}
                    </a>
                    <a
                        href="{{ route('export.rfq.excel', $request) }}"
                        class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                        @click="open = false"
                    >
                        <x-icon name="table-cells" class="w-4 h-4" />
                        {{ __('RFQ as Excel') }}
                    </a>
                    @if($request->quotes()->where('status', 'submitted')->count() > 0)
                        <a
                            href="{{ route('export.quote-comparison.pdf', $request) }}"
                            target="_blank"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                            @click="open = false"
                        >
                            <x-icon name="document-chart-bar" class="w-4 h-4" />
                            {{ __('Quote Comparison PDF') }}
                        </a>
                        <a
                            href="{{ route('export.quotes.excel', $request) }}"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border-t border-gray-200 dark:border-gray-700"
                            @click="open = false"
                        >
                            <x-icon name="table-cells" class="w-4 h-4" />
                            {{ __('Quotes as Excel') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <x-button icon="arrow-left" href="{{ route('monitoring.rfq.index') }}" color="white">
            @lang('Back to RFQs')
        </x-button>
        @can('edit_rfqs')
            <x-button
                icon="pencil"
                color="lime"
                wire:click="$dispatch('monitoring::load::rfq', { rfq: '{{ $request->id }}' })"
            >
                @lang('Edit Request')
            </x-button>
        @endcan
    </div>

    {{-- Main Content Card --}}
    <div class="relative overflow-hidden rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-gray-200/50 dark:border-slate-700/50 shadow-xl">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 via-transparent to-cyan-500/5 dark:from-blue-500/10 dark:to-cyan-500/10"></div>

        <div class="relative p-6 space-y-6">
            {{-- RFQ Details --}}
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    @lang('RFQ Details')
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase mb-1">@lang('Buyer')</p>
                        <p class="text-gray-900 dark:text-gray-100 font-medium">
                            {{ $request->buyer?->name ?? __('Unknown') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase mb-1">@lang('Created at')</p>
                        <p class="text-gray-900 dark:text-gray-100 font-medium">
                            {{ $request->created_at->format('Y-m-d H:i') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase mb-1">@lang('Deadline')</p>
                        <p class="text-gray-900 dark:text-gray-100 font-medium">
                            {{ optional($request->deadline)->format('Y-m-d') ?? '—' }}
                        </p>
                    </div>
                </div>

                {{-- Status Change --}}
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="w-full md:w-64">
                            <x-select.styled
                                label="{{ __('Change Status') }}"
                                wire:model.live="statusValue"
                                :options="collect($availableStatuses)->map(fn($label, $value) => ['label' => __($label), 'value' => $value])->values()->toArray()"
                                select="label:label|value:value"
                            />
                        </div>
                        <div class="flex items-end">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                <p class="mb-1">
                                    <strong>@lang('Current Status'):</strong>
                                    <x-badge
                                        :text="ucfirst(__($request->status))"
                                        :color="match($request->status) {
                                            'open' => 'green',
                                            'closed' => 'red',
                                            'awarded' => 'blue',
                                            'cancelled' => 'gray',
                                            default => 'yellow'
                                        }"
                                    />
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($request->description)
                    <div class="mt-4">
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase mb-1">@lang('Description')</p>
                        <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $request->description }}</p>
                    </div>
                @endif
            </div>

            {{-- RFQ Items --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                    @lang('Items') ({{ $request->items->count() }})
                </h3>

                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                @lang('Product')
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                @lang('Quantity')
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                @lang('Specifications')
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($request->items as $item)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $item->product_name ?? __('Unknown') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 text-right">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $item->specifications ?: '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                    @lang('No items found.')
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Supplier Invitations --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        @lang('Supplier Invitations') ({{ $this->invitationRows->total() }})
                    </h3>
                    <x-button
                        wire:click="openInviteModal"
                        icon="user-plus"
                        color="blue"
                        sm
                    >
                        @lang('Invite More Suppliers')
                    </x-button>
                </div>

                @if($this->invitationRows->isEmpty())
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            @lang('No suppliers invited yet.')
                        </p>
                    </div>
                @else
                    <x-table :headers="$headers" :sort="$sort" :rows="$this->invitationRows" paginate :paginator="null" :quantity="[10]">
                        @interact('column_supplier', $row)
                        <div class="text-sm text-gray-900 dark:text-gray-100">
                            <div class="font-medium">{{ $row->supplier?->name ?? __('Unknown Supplier') }}</div>
                            @if($row->supplier?->company_name)
                                <div class="text-xs text-gray-500">{{ $row->supplier->company_name }}</div>
                            @endif
                        </div>
                        @endinteract

                        @interact('column_status', $row)
                        <x-badge
                            :text="ucfirst(__($row->status))"
                            :color="match($row->status) {
                                    'pending' => 'yellow',
                                    'accepted' => 'blue',
                                    'declined' => 'red',
                                    'quoted' => 'green',
                                    default => 'gray'
                                }"
                        />
                        @endinteract

                        @interact('column_sent_at', $row)
                        <span class="text-sm text-gray-900 dark:text-gray-100">
                                {{ $row->sent_at ? $row->sent_at->format('M d, Y H:i') : '—' }}
                            </span>
                        @endinteract

                        @interact('column_responded_at', $row)
                        <span class="text-sm text-gray-900 dark:text-gray-100">
                                {{ $row->responded_at ? $row->responded_at->format('M d, Y H:i') : '—' }}
                            </span>
                        @endinteract

                        @interact('column_action', $row)
                        <div class="flex justify-end">
                            @if($row->status === 'pending')
                                <x-button.circle
                                    type="button"
                                    icon="trash"
                                    color="red"
                                    sm
                                    wire:click="deleteInvitation({{ $row->id }})"
                                />
                            @endif
                        </div>
                        @endinteract
                    </x-table>
                @endif
            </div>

            {{-- Quote Analysis & Summary --}}
            @if($request->quotes->count() > 0)
                @php
                    $submittedQuotes = $request->quotes->sortBy('total_amount');
                    $lowestQuote = $submittedQuotes->first();
                    $highestQuote = $submittedQuotes->last();
                    $avgQuote = $submittedQuotes->avg('total_amount');
                @endphp

                {{-- Quote Analysis Summary Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg p-4 border border-green-200 dark:border-green-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-green-600 dark:text-green-400 uppercase">@lang('Best Quote')</p>
                                <p class="text-2xl font-bold text-green-900 dark:text-green-100 mt-1">
                                    ${{ number_format($lowestQuote->total_amount, 2) }}
                                </p>
                                <p class="text-xs text-green-600 dark:text-green-400 mt-1">{{ $lowestQuote->supplier->name ?? 'N/A' }}</p>
                            </div>
                            <x-icon name="arrow-trending-down" class="w-8 h-8 text-green-500" />
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-lg p-4 border border-red-200 dark:border-red-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-red-600 dark:text-red-400 uppercase">@lang('Highest Quote')</p>
                                <p class="text-2xl font-bold text-red-900 dark:text-red-100 mt-1">
                                    ${{ number_format($highestQuote->total_amount, 2) }}
                                </p>
                                <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $highestQuote->supplier->name ?? 'N/A' }}</p>
                            </div>
                            <x-icon name="arrow-trending-up" class="w-8 h-8 text-red-500" />
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg p-4 border border-blue-200 dark:border-blue-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-blue-600 dark:text-blue-400 uppercase">@lang('Average Quote')</p>
                                <p class="text-2xl font-bold text-blue-900 dark:text-blue-100 mt-1">
                                    ${{ number_format($avgQuote, 2) }}
                                </p>
                                <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">{{ $submittedQuotes->count() }} @lang('quotes')</p>
                            </div>
                            <x-icon name="chart-bar" class="w-8 h-8 text-blue-500" />
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-lg p-4 border border-purple-200 dark:border-purple-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-purple-600 dark:text-purple-400 uppercase">@lang('Price Range')</p>
                                <p class="text-2xl font-bold text-purple-900 dark:text-purple-100 mt-1">
                                    ${{ number_format($highestQuote->total_amount - $lowestQuote->total_amount, 2) }}
                                </p>
                                <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">
                                    {{ number_format((($highestQuote->total_amount - $lowestQuote->total_amount) / $lowestQuote->total_amount) * 100, 1) }}% @lang('variance')
                                </p>
                            </div>
                            <x-icon name="arrows-up-down" class="w-8 h-8 text-purple-500" />
                        </div>
                    </div>
                </div>

                {{-- Quote Comparison Table --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 mb-6 overflow-hidden">
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 border-b border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                            <x-icon name="table-cells" class="w-5 h-5" />
                            @lang('Quote Comparison Summary')
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">@lang('Supplier')</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">@lang('Total Amount')</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">@lang('Delivery Time')</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">@lang('Payment Terms')</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">@lang('Validity')</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">@lang('Status')</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">@lang('Rank')</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($submittedQuotes as $index => $quote)
                                    <tr class="{{ $index === 0 ? 'bg-green-50 dark:bg-green-900/20' : '' }}">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $quote->supplier->name ?? 'N/A' }}
                                            @if($index === 0)
                                                <x-badge text="{{ __('Best') }}" color="green" class="ml-2" sm />
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 text-right font-semibold">
                                            ${{ number_format($quote->total_amount, 2) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 text-right">
                                            {{ $quote->delivery_time ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $quote->payment_terms ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 text-right">
                                            {{ $quote->validity_days ?? 'N/A' }} @lang('days')
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <x-badge
                                                :text="ucfirst(str_replace('_', ' ', $quote->status))"
                                                :color="match($quote->status) {
                                                    'submitted' => 'blue',
                                                    'under_review' => 'yellow',
                                                    'accepted', 'won' => 'green',
                                                    'rejected', 'lost' => 'red',
                                                    default => 'gray'
                                                }"
                                                sm
                                            />
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $index === 0 ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 font-bold' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                                                #{{ $index + 1 }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Detailed Quotes Section --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        @lang('Detailed Quotes') ({{ $request->quotes->count() }})
                    </h3>
                </div>

                @if($request->quotes->isEmpty())
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            @lang('No quotes submitted yet.')
                        </p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($request->quotes as $quote)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                <details class="group">
                                    {{-- ACCORDION HEADER --}}
                                    <summary
                                        class="list-none cursor-pointer bg-gray-100 dark:bg-gray-800 px-4 py-3 flex items-center justify-between gap-4"
                                    >
                                        {{-- LEFT --}}
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $quote->supplier?->name ?? __('Unknown Supplier') }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                @lang('Submitted'):
                                                {{ $quote->submitted_at?->format('M d, Y H:i') ?? $quote->created_at->format('M d, Y H:i') }}
                                            </div>
                                        </div>

                                        {{-- RIGHT --}}
                                        <div class="flex items-center gap-4 shrink-0">
                                            {{-- TOTAL --}}
                                            <div class="text-right">
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    @lang('Total Amount')
                                                </div>
                                                <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                                    ${{ number_format($quote->total_amount ?? 0, 2) }}
                                                </div>
                                            </div>

                                            {{-- STATUS --}}
                                            <x-badge
                                                :text="ucfirst(str_replace('_', ' ', __($quote->status) ?? 'submitted'))"
                                                :color="match($quote->status) {
                                                    'submitted' => 'blue',
                                                    'accepted', 'won' => 'green',
                                                    'rejected', 'lost' => 'red',
                                                    default => 'gray'
                                                }"
                                            />

                                            {{-- ACTIONS --}}
                                            @if(!in_array($quote->status, ['accepted', 'rejected']))
                                                <div class="flex gap-2" @click.stop>
                                                    <x-button
                                                        color="green"
                                                        sm
                                                        icon="check-circle"
                                                        wire:click="confirmAcceptQuote({{ $quote->id }})"
                                                    >
                                                        @lang('Accept')
                                                    </x-button>

                                                    <x-button
                                                        color="red"
                                                        sm
                                                        icon="x-circle"
                                                        wire:click="confirmRejectQuote({{ $quote->id }})"
                                                    >
                                                        @lang('Reject')
                                                    </x-button>
                                                </div>
                                            @endif

                                            {{-- CHEVRON --}}
                                            <x-icon name="chevron-down" class="w-4 h-4 text-gray-400 transition-transform group-open:rotate-180" />
                                        </div>
                                    </summary>

                                    {{-- ACCORDION CONTENT --}}
                                    <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 space-y-6">
                                        {{-- ITEMS --}}
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full text-sm">
                                                <thead class="bg-gray-50 dark:bg-gray-800">
                                                <tr>
                                                    <th class="px-3 py-2 text-left">@lang('Product')</th>
                                                    <th class="px-3 py-2 text-right">@lang('Qty')</th>
                                                    <th class="px-3 py-2 text-right">@lang('Unit Price')</th>
                                                    <th class="px-3 py-2 text-right">@lang('Total')</th>
                                                </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                                @foreach($quote->items as $item)
                                                    <tr>
                                                        <td class="px-3 py-2">
                                                            <div class="font-medium text-gray-900 dark:text-gray-100">
                                                                {{ $item->requestItem?->product_name ?? 'Unknown' }}
                                                            </div>
                                                            <div class="text-xs text-gray-500">
                                                                {{ $item->notes }}
                                                            </div>
                                                        </td>
                                                        <td class="px-3 py-2 text-right">
                                                            {{ $item->quantity }}
                                                        </td>
                                                        <td class="px-3 py-2 text-right">
                                                            ${{ number_format($item->unit_price ?? 0, 2) }}
                                                        </td>
                                                        <td class="px-3 py-2 text-right font-medium">
                                                            ${{ number_format($item->total ?? 0, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        {{-- NOTES / TERMS --}}
                                        @if($quote->notes || $quote->terms_conditions)
                                            <div class="text-sm space-y-3">
                                                @if($quote->notes)
                                                    <p>
                                                        <span class="font-medium">@lang('Notes'):</span>
                                                        {{ $quote->notes }}
                                                    </p>
                                                @endif

                                                @if($quote->terms_conditions)
                                                    <p>
                                                        <span class="font-medium">@lang('Terms & Conditions'):</span>
                                                        {{ $quote->terms_conditions }}
                                                    </p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </details>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Invite Suppliers Modal --}}
    <x-modal :title="__('Invite More Suppliers')" wire="showInviteModal" blur="xl" size="2xl">
        <div class="space-y-4">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                @lang('Select suppliers to invite for this RFQ. They will receive a notification.')
            </p>

            @if($this->availableSuppliers->isEmpty())
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                    <div class="flex items-center gap-2">
                        <x-icon name="exclamation-triangle" class="w-5 h-5 text-yellow-600 dark:text-yellow-500"/>
                        <p class="text-sm text-yellow-800 dark:text-yellow-300">
                            @lang('All available suppliers have already been invited to this RFQ.')
                        </p>
                    </div>
                </div>
            @else
                <div>
                    <x-select.styled
                        label="{{ __('Select Suppliers') }}"
                        wire:model="selectedSuppliers"
                        :options="$this->availableSuppliers"
                        select="label:name|value:id"
                        searchable
                        multiple
                    />
                </div>

                @if(!empty($selectedSuppliers))
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                        <p class="text-sm text-blue-800 dark:text-blue-300">
                            <strong>{{ count($selectedSuppliers) }}</strong> {{ __('supplier(s) selected') }}
                        </p>
                    </div>
                @endif
            @endif
        </div>

        <x-slot:footer>
            <div class="flex gap-3 justify-end">
                <x-button color="white" wire:click="closeInviteModal">
                    @lang('Cancel')
                </x-button>
                @if(!$this->availableSuppliers->isEmpty())
                    <x-button color="blue" wire:click="inviteSuppliers">
                        @lang('Send Invitations')
                    </x-button>
                @endif
            </div>
        </x-slot:footer>
    </x-modal>

    <livewire:monitoring.rfq.update @updated="$refresh"/>
</div>
