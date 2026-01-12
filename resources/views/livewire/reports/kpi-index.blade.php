<div class="space-y-6">
    {{-- Header --}}
    <x-card>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('KPI Reports & Analytics') }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Performance metrics and insights') }}</p>
            </div>
        </div>

        <div class="flex gap-2">
            <x-button
                :color="$reportType === 'overview' ? 'primary' : 'secondary'"
                wire:click="$set('reportType', 'overview')"
            >
                {{ __('Overview') }}
            </x-button>
            <x-button
                :color="$reportType === 'suppliers' ? 'primary' : 'secondary'"
                wire:click="$set('reportType', 'suppliers')"
            >
                {{ __('Supplier Performance') }}
            </x-button>
            <x-button
                :color="$reportType === 'rfqs' ? 'primary' : 'secondary'"
                wire:click="$set('reportType', 'rfqs')"
            >
                {{ __('RFQ Execution') }}
            </x-button>
        </div>
    </x-card>

    @if($reportType === 'overview')
        {{-- Aggregate KPIs --}}
        <x-card>
            <h2 class="text-lg font-semibold mb-4">{{ __('Aggregate KPIs') }}</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat
                    label="{{ __('Total RFQs') }}"
                    :value="$aggregate['total_rfqs']"
                    icon="document-text"
                />
                <x-stat
                    label="{{ __('Avg RFQ Execution') }}"
                    :value="$aggregate['avg_rfq_execution_days'] . ' days'"
                    icon="clock"
                />
                <x-stat
                    label="{{ __('Avg Supplier Response') }}"
                    :value="$aggregate['avg_supplier_response_days'] . ' days'"
                    icon="paper-airplane"
                />
                <x-stat
                    label="{{ __('Quote Acceptance Rate') }}"
                    :value="$aggregate['overall_quote_acceptance_rate'] . '%'"
                    icon="chart-bar"
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <x-stat
                    label="{{ __('Completed RFQs') }}"
                    :value="$aggregate['completed_rfqs']"
                    icon="check-circle"
                />
                <x-stat
                    label="{{ __('Total Quotes') }}"
                    :value="$aggregate['total_quotes']"
                    icon="document-duplicate"
                />
                <x-stat
                    label="{{ __('Accepted Quotes') }}"
                    :value="$aggregate['accepted_quotes']"
                    icon="check-badge"
                />
            </div>
        </x-card>

        {{-- Top Suppliers by Response Speed --}}
        <x-card>
            <h2 class="text-lg font-semibold mb-4">{{ __('Top Suppliers by Response Speed') }}</h2>

            <x-table
                :headers="$topSuppliersHeaders"
                :rows="$this->topSuppliers"
            >
                @interact('column_rank', $row)
                    <span class="font-bold text-lg">{{ $loop->iteration }}</span>
                @endinteract

                @interact('column_supplier', $row)
                    <a href="{{ route('users.show', $row['supplier']) }}" class="text-blue-600 hover:underline font-medium">
                        {{ $row['supplier']->name }}
                    </a>
                @endinteract

                @interact('column_response_time', $row)
                    <div class="flex flex-col">
                        <span class="font-semibold">{{ $row['metrics']['avg_response_time_hours'] }}h</span>
                        <span class="text-xs text-gray-500">{{ $row['metrics']['avg_response_time_days'] }} days</span>
                    </div>
                @endinteract

                @interact('column_response_rate', $row)
                    <x-badge :text="$row['metrics']['response_rate'] . '%'" :color="$row['metrics']['response_rate'] >= 80 ? 'green' : 'yellow'" />
                @endinteract

                @interact('column_win_rate', $row)
                    <x-badge :text="$row['metrics']['win_rate'] . '%'" :color="$row['metrics']['win_rate'] >= 50 ? 'green' : 'slate'" />
                @endinteract
            </x-table>
        </x-card>

        {{-- Slowest RFQs --}}
        <x-card>
            <h2 class="text-lg font-semibold mb-4">{{ __('RFQs with Longest Execution Time') }}</h2>

            <x-table
                :headers="$slowestRfqsHeaders"
                :rows="$this->slowestRfqs"
            >
                @interact('column_rfq', $row)
                    <a href="{{ route('rfq.show', $row['rfq_id']) }}" class="text-blue-600 hover:underline">
                        {{ $row['rfq_title'] }}
                    </a>
                @endinteract

                @interact('column_status', $row)
                    <x-badge :text="ucfirst($row['status'])" />
                @endinteract

                @interact('column_execution_time', $row)
                    <div class="flex flex-col">
                        <span class="font-semibold">{{ $row['execution_time_days'] }} days</span>
                        <span class="text-xs text-gray-500">{{ $row['execution_time_hours'] }}h</span>
                    </div>
                @endinteract

                @interact('column_quotes', $row)
                    <x-badge :text="$row['total_quotes_received']" color="slate" />
                @endinteract
            </x-table>
        </x-card>
    @endif

    @if($reportType === 'suppliers')
        <x-card>
            <h2 class="text-lg font-semibold mb-4">{{ __('Supplier Performance Metrics') }}</h2>

            <div class="mb-4 flex items-center justify-between">
                <div class="w-full sm:w-64">
                    <x-input icon="magnifying-glass" wire:model.live.debounce.300ms="search" placeholder="{{__('Search suppliers...')}}"/>
                </div>
            </div>

            <x-table
                :headers="$supplierHeaders"
                :rows="$this->suppliers"
                paginate
                :paginator="null"
                :quantity="[10, 20, 50, 'all']"
            >
                @interact('column_supplier', $row)
                    <a href="{{ route('users.show', $row->supplier) }}" class="text-blue-600 hover:underline font-medium">
                        {{ $row->supplier->name }}
                    </a>
                @endinteract

                @interact('column_invitations', $row)
                    {{ $row->metrics['total_invitations'] }} / {{ $row->metrics['total_responses'] }}
                @endinteract

                @interact('column_response_rate', $row)
                    <x-badge
                        :text="$row->metrics['response_rate'] . '%'"
                        :color="$row->metrics['response_rate'] >= 80 ? 'green' : ($row->metrics['response_rate'] >= 50 ? 'yellow' : 'red')"
                    />
                @endinteract

                @interact('column_avg_response', $row)
                    {{ $row->metrics['avg_response_time_hours'] }}h ({{ $row->metrics['avg_response_time_days'] }}d)
                @endinteract

                @interact('column_quotes', $row)
                    {{ $row->metrics['total_quotes_submitted'] }}
                @endinteract

                @interact('column_win_rate', $row)
                    <x-badge
                        :text="$row->metrics['win_rate'] . '%'"
                        :color="$row->metrics['win_rate'] >= 50 ? 'green' : 'slate'"
                    />
                @endinteract
            </x-table>
        </x-card>
    @endif

    @if($reportType === 'rfqs')
        <x-card>
            <h2 class="text-lg font-semibold mb-4">{{ __('RFQ Execution Metrics') }}</h2>

            <div class="mb-4 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                <div class="w-full sm:w-64">
                    <x-input icon="magnifying-glass" wire:model.live.debounce.300ms="search" placeholder="{{__('Search RFQs...')}}"/>
                </div>

                <div class="w-full sm:w-48">
                    <x-select.styled
                        :label="__('Filter by Status')"
                        wire:model.live="statusFilter"
                        :options="[
                            ['label' => 'All Status', 'value' => ''],
                            ['label' => 'Draft', 'value' => 'draft'],
                            ['label' => 'Open', 'value' => 'open'],
                            ['label' => 'Closed', 'value' => 'closed'],
                            ['label' => 'Awarded', 'value' => 'awarded'],
                            ['label' => 'Cancelled', 'value' => 'cancelled'],
                        ]"
                        select="label:label|value:value"
                    />
                </div>
            </div>

            <x-table
                :headers="$rfqHeaders"
                :$sort
                :rows="$this->rfqs"
                paginate
                :paginator="null"
                :quantity="[10, 20, 50, 'all']"
            >
                @interact('column_rfq', $row)
                    <a href="{{ route('rfq.show', $row->rfq_id) }}" class="text-blue-600 hover:underline">
                        {{ $row->rfq_title }}
                    </a>
                @endinteract

                @interact('column_status', $row)
                    <x-badge :text="ucfirst($row->status)" />
                @endinteract

                @interact('column_execution', $row)
                    <div class="flex flex-col">
                        <span class="font-semibold">{{ $row->execution_time_days }} days</span>
                        <span class="text-xs text-gray-500">{{ $row->execution_time_hours }}h</span>
                    </div>
                @endinteract

                @interact('column_first_quote', $row)
                    @if($row->time_to_first_quote_hours)
                        {{ round($row->time_to_first_quote_hours, 1) }}h
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                @endinteract

                @interact('column_quotes', $row)
                    <x-badge :text="$row->total_quotes_received" color="slate" />
                @endinteract
            </x-table>
        </x-card>
    @endif
</div>
