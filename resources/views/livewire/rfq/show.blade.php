<div>
    <x-card>
        {{-- Header / Title + Actions --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ $request->title }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    @lang('RFQ #:id', ['id' => $request->id])
                </p>
            </div>

            <div class="flex flex-col md:flex-row items-end md:items-center gap-3">
                <div class="flex flex-col items-end">
                    <x-badge
                        :text="ucfirst($request->status)"
                        :color="match($request->status) {
                            'open' => 'green',
                            'closed' => 'red',
                            default => 'gray'
                        }"
                    />
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        @lang('Deadline'):
                        {{ optional($request->deadline)->format('Y-m-d') ?? '—' }}
                    </div>
                </div>

                <div class="flex gap-2">
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
                            class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50"
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

                    <x-button
                        icon="pencil"
                        color="lime"
                        wire:click="$dispatch('load::rfq', { rfq: '{{ $request->id }}' })"
                    >
                        @lang('Edit Request')
                    </x-button>

                    <x-button icon="arrow-left" href="{{ route('rfq.index') }}">
                        @lang('Back to RFQs')
                    </x-button>

                    {{--                    <x-button color="purple" icon="arrow-right" href="{{ route('rfq.quote', $request->id) }}">--}}
                    {{--                        @lang('Quote (Only Supplier)')--}}
                    {{--                    </x-button>--}}

                    @if($canQuote)
                        <x-button icon="currency-dollar" :href="route('rfq.quote', $request)">
                            @lang('Submit Quote')
                        </x-button>
                    @endif
                </div>
            </div>
        </div>

        <div class="w-70 mb-6">
            <x-select.styled
                label="{{ __('Change Status') }}"
                wire:model.live="statusValue"
                :options="collect($availableStatuses)->map(fn($label, $value) => ['label' => __($label), 'value' => $value])->values()->toArray()"
                select="label:label|value:value"
            />
        </div>

        @if($isAdmin)
            {{-- Admin Controls Section --}}
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-2 mb-4">
                    <x-icon name="cog" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                    <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100">
                        @lang('Admin Controls')
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Request Type --}}
                    <div>
                        <x-select.styled
                            label="{{ __('Request Type') }}"
                            wire:model.live="requestTypeValue"
                            :options="collect($availableRequestTypes)->map(fn($label, $value) => ['label' => __($label), 'value' => $value])->values()->toArray()"
                            select="label:label|value:value"
                        />
                    </div>

                    {{-- Requires Field Assessment Toggle --}}
                    <div class="flex flex-col">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Field Assessment') }}
                        </label>
                        <div class="flex items-center h-10">
                            <input
                                type="checkbox"
                                wire:model.live="requiresFieldAssessmentValue"
                                id="requires_field_assessment"
                                class="rounded border-gray-300 dark:border-gray-700 text-blue-600 shadow-sm focus:ring-blue-500 w-5 h-5"
                            >
                            <label for="requires_field_assessment" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Requires Field Assessment') }}
                            </label>
                        </div>
                    </div>

                    {{-- Field Evaluator Assignment --}}
                    @if($request->requires_field_assessment)
                        <div>
                            <x-select.styled
                                label="{{ __('Field Evaluator') }}"
                                wire:model.live="fieldEvaluatorId"
                                :options="$fieldEvaluators"
                                select="label:name|value:id"
                                searchable
                                :placeholder="__('Assign evaluator')"
                            />
                        </div>
                    @endif
                </div>

                {{-- Field Assessment Status Display --}}
                @if($request->requires_field_assessment && $request->latestFieldAssessment)
                    <div class="mt-4 pt-4 border-t border-blue-200 dark:border-blue-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-900 dark:text-blue-100">
                                    @lang('Field Assessment Status'):
                                    <x-badge
                                        :text="ucfirst(str_replace('_', ' ', $request->field_assessment_status))"
                                        :color="match($request->field_assessment_status) {
                                            'pending' => 'yellow',
                                            'in_progress' => 'blue',
                                            'completed' => 'green',
                                            default => 'gray'
                                        }"
                                    />
                                </p>
                                @if($request->fieldEvaluator)
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                        @lang('Assigned to'): {{ $request->fieldEvaluator->name }}
                                    </p>
                                @endif
                            </div>
                            @if($request->field_assessment_completed_at)
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    @lang('Completed'): {{ $request->field_assessment_completed_at->format('Y-m-d H:i') }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <div class="space-y-6">
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
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase mb-1">@lang('Request Type')</p>
                        <div>
                            <x-badge
                                :text="$request->request_type === 'public' ? __('Public Tender') : __('Internal')"
                                :color="$request->request_type === 'public' ? 'blue' : 'gray'"
                            />
                        </div>
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
                    @if($request->delivery_location)
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-xs uppercase mb-1">@lang('Delivery Location')</p>
                            <p class="text-gray-900 dark:text-gray-100 font-medium">
                                {{ $request->delivery_location }}
                            </p>
                        </div>
                    @endif
                    @if($request->delivery_address)
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-xs uppercase mb-1">@lang('Delivery Address')</p>
                            <p class="text-gray-900 dark:text-gray-100 font-medium">
                                {{ $request->delivery_address }}
                            </p>
                        </div>
                    @endif
                </div>

                @if($request->description)
                    <div class="mt-4">
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase mb-1">@lang('Description')</p>
                        <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $request->description }}</p>
                    </div>
                @endif

                @if($request->special_instructions)
                    <div class="mt-4">
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase mb-1">@lang('Special Instructions')</p>
                        <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $request->special_instructions }}</p>
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
                                    {{ $item->product_name ?? __('Unknown product') }}
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

            {{-- Quotes Analysis & Summary --}}
            @if($request->quotes->count() > 0)
                @php
                    $submittedQuotes = $request->quotes->sortBy('total_amount');
                    $lowestQuote = $submittedQuotes->first();
                    $highestQuote = $submittedQuotes->last();
                    $avgQuote = $submittedQuotes->avg('total_amount');
                    $priceRange = $highestQuote->total_amount - $lowestQuote->total_amount;
                    $variancePercent = $lowestQuote->total_amount > 0 ? ($priceRange / $lowestQuote->total_amount) * 100 : 0;
                @endphp

                {{-- Quote Analysis Summary Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg p-4 border border-green-200 dark:border-green-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-green-600 dark:text-green-400 uppercase">@lang('Best Quote')</p>
                                <p class="text-2xl font-bold text-green-900 dark:text-green-100 mt-1">
                                    {{ $lowestQuote->currency ?? 'AZN' }} {{ number_format($lowestQuote->total_amount ?? 0, 2) }}
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
                                    {{ $highestQuote->currency ?? 'AZN' }} {{ number_format($highestQuote->total_amount ?? 0, 2) }}
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
                                    {{ $lowestQuote->currency ?? 'AZN' }} {{ number_format($avgQuote ?? 0, 2) }}
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
                                    {{ number_format($priceRange, 2) }}
                                </p>
                                <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">
                                    {{ number_format($variancePercent, 1) }}% @lang('variance')
                                </p>
                            </div>
                            <x-icon name="arrows-up-down" class="w-8 h-8 text-purple-500" />
                        </div>
                    </div>
                </div>

                {{-- Quote Comparison Table (Compact View) --}}
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
                                            {{ $quote->currency ?? 'AZN' }} {{ number_format($quote->total_amount, 2) }}
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

                    @if($request->latestFieldAssessment && $request->latestFieldAssessment->recommended_price)
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border-t border-yellow-200 dark:border-yellow-700 px-4 py-3">
                            <div class="flex items-start gap-2">
                                <x-icon name="light-bulb" class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5" />
                                <div>
                                    <p class="text-sm font-medium text-yellow-900 dark:text-yellow-100">
                                        @lang('Field Assessment Recommendation')
                                    </p>
                                    <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                                        @lang('Recommended Price'): <strong>{{ $request->latestFieldAssessment->currency ?? 'AZN' }} {{ number_format($request->latestFieldAssessment->recommended_price, 2) }}</strong>
                                    </p>
                                    @if($request->latestFieldAssessment->notes)
                                        <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">{{ $request->latestFieldAssessment->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Detailed Quotes Section --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        @lang('Detailed Quotes') ({{ $request->quotes->count() }})
                    </h3>

                    @if($canQuote)
                        <x-button size="sm" icon="currency-dollar" :href="route('rfq.quote', $request)">
                            @lang('Submit Quote')
                        </x-button>
                    @endif
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
                                {{-- Quote Header --}}
                                <div class="bg-gray-100 dark:bg-gray-800 px-4 py-3 flex justify-between items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $quote->supplier?->name ?? __('Unknown Supplier') }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            @lang('Submitted'): {{ $quote->submitted_at ? $quote->submitted_at->format('M d, Y H:i') : $quote->created_at->format('M d, Y H:i') }}
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500 dark:text-gray-400">@lang('Total Amount')</div>
                                            <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $quote->currency ?? 'USD' }} ${{ number_format($quote->total_amount ?? $quote->total_price, 2) }}
                                            </div>
                                        </div>
                                        <x-badge
                                            :text="ucfirst(str_replace('_', ' ', $quote->status ?? 'submitted'))"
                                            :color="match($quote->status) {
                                                'draft' => 'gray',
                                                'submitted' => 'blue',
                                                'under_review' => 'yellow',
                                                'accepted', 'won' => 'green',
                                                'rejected', 'lost' => 'red',
                                                default => 'gray'
                                            }"
                                        />
                                    </div>
                                </div>

                                {{-- Quote Items --}}
                                @if($quote->items && $quote->items->count() > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                                                    @lang('Item')
                                                </th>
                                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                                                    @lang('Qty')
                                                </th>
                                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                                                    @lang('Unit Price')
                                                </th>
                                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                                                    @lang('Tax %')
                                                </th>
                                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">
                                                    @lang('Total')
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($quote->items as $item)
                                                <tr>
                                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $item->description }}
                                                        @if($item->notes)
                                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $item->notes }}</div>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 text-right">
                                                        {{ $item->quantity }}
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 text-right">
                                                        ${{ number_format($item->unit_price, 2) }}
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 text-right">
                                                        {{ number_format($item->tax_rate, 1) }}%
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100 text-right font-medium">
                                                        ${{ number_format($item->total, 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                {{-- Quote Details --}}
                                <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                                        @if($quote->valid_until)
                                            <div>
                                                <span class="text-gray-500 dark:text-gray-400">@lang('Valid Until'):</span>
                                                <span class="ml-1 text-gray-900 dark:text-gray-100">{{ $quote->valid_until->format('M d, Y') }}</span>
                                            </div>
                                        @endif
                                        @if($quote->notes)
                                            <div class="md:col-span-2">
                                                <span class="text-gray-500 dark:text-gray-400">@lang('Notes'):</span>
                                                <span class="ml-1 text-gray-900 dark:text-gray-100">{{ $quote->notes }}</span>
                                            </div>
                                        @endif
                                        @if($quote->terms_conditions)
                                            <div class="md:col-span-3">
                                                <span class="text-gray-500 dark:text-gray-400">@lang('Terms & Conditions'):</span>
                                                <div class="ml-1 text-gray-900 dark:text-gray-100 mt-1 text-sm whitespace-pre-line">{{ $quote->terms_conditions }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </x-card>

    <livewire:rfq.update @updated="$refresh"/>
</div>
