<div>
    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-card class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Logs') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($this->statistics['total'] ?? 0) }}
                    </p>
                </div>
                <div class="p-3 bg-blue-500 rounded-full">
                    <x-icon name="clipboard-document-list" class="h-6 w-6 text-white"/>
                </div>
            </div>
        </x-card>

        <x-card class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Today') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($this->statistics['today'] ?? 0) }}
                    </p>
                </div>
                <div class="p-3 bg-green-500 rounded-full">
                    <x-icon name="calendar" class="h-6 w-6 text-white"/>
                </div>
            </div>
        </x-card>

        <x-card class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('This Week') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($this->statistics['this_week'] ?? 0) }}
                    </p>
                </div>
                <div class="p-3 bg-purple-500 rounded-full">
                    <x-icon name="chart-bar" class="h-6 w-6 text-white"/>
                </div>
            </div>
        </x-card>

        <x-card class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Types') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ count($this->logTypes ?? []) }}
                    </p>
                </div>
                <div class="p-3 bg-orange-500 rounded-full">
                    <x-icon name="tag" class="h-6 w-6 text-white"/>
                </div>
            </div>
        </x-card>
    </div>

    {{-- Main Logs Table Card --}}
    <x-card>
        {{-- Header Section --}}
        <div class="flex items-center justify-between mb-4">
            <x-heading-title
                title="{{ __('Activity Logs') }}"
                text="{{ __('Monitor system activities and user actions') }}"
                icon="clipboard-document-list"
                padding="p-5"
                hover="-"
            />

            <div class="flex gap-2">
                @can('export_logs')
                    <x-button
                        icon="arrow-down-tray"
                        color="secondary"
                        wire:click="exportLogs"
                    >
                        {{ __('Export') }}
                    </x-button>
                @endcan

                @can('view_logs')
                    <x-button
                        icon="document-text"
                        color="secondary"
                        href="{{ route('export.audit.pdf') }}"
                        target="_blank"
                    >
                        {{ __('Audit Trail') }}
                    </x-button>
                @endcan
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="mb-4 mt-4 flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[250px]">
                <x-input
                    label="{{ __('Search') }}"
                    wire:model.live.debounce.300ms="search"
                    placeholder="{{ __('Search logs by type, action, message, IP or user...') }}"
                    icon="magnifying-glass"
                />
            </div>

            <div class="flex-1 min-w-[200px]">
                <x-select.styled
                    label="{{ __('Filter by Type') }}"
                    wire:model.live="typeFilter"
                    :options="$this->logTypeOptions"
                    select="label:label|value:value"
                    placeholder="{{ __('All Types') }}"
                />
            </div>

            @if($typeFilter || $search)
                <x-button
                    color="red"
                    icon="x-mark"
                    text="{{ __('Clear Filters') }}"
                    wire:click="clearAllFilters"
                />
            @endif
        </div>

        {{-- Loading Indicator --}}
        <div wire:loading.delay class="mb-3">
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg px-4 py-2 flex items-center gap-2">
                <x-icon name="arrow-path" class="h-4 w-4 animate-spin text-blue-600 dark:text-blue-400"/>
                <span class="text-sm text-blue-600 dark:text-blue-400">{{ __('Loading logs...') }}</span>
            </div>
        </div>

        {{-- Empty State --}}
        @if($this->rows->isEmpty() && !$search && !$typeFilter)
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                    <x-icon name="clipboard-document-list" class="h-8 w-8 text-gray-400"/>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    {{ __('No logs yet') }}
                </h3>
                <p class="text-gray-500 dark:text-gray-400">
                    {{ __('System activity logs will appear here') }}
                </p>
            </div>
        @elseif($this->rows->isEmpty())
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                    <x-icon name="magnifying-glass" class="h-8 w-8 text-gray-400"/>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    {{ __('No results found') }}
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4">
                    {{ __('Try adjusting your search or filter criteria') }}
                </p>
                <x-button
                    color="secondary"
                    wire:click="clearAllFilters"
                >
                    {{ __('Clear Filters') }}
                </x-button>
            </div>
        @else
            {{-- Table --}}
            <x-table
                :$headers
                :$sort
                :rows="$this->rows"
                paginate
                :paginator="null"
                filter
                loading
                :quantity="[5, 10, 20, 50, 'all']"
            >
                {{-- ID Column --}}
                @interact('column_id', $row)
                <span class="text-xs font-mono text-gray-500 dark:text-gray-400">
                        #{{ str_pad($row->id, 5, '0', STR_PAD_LEFT) }}
                    </span>
                @endinteract

                {{-- Type Column with Enhanced Badge --}}
                @interact('column_type', $row)
                @php
                    $typeConfig = $this->getTypeConfig($row->type ?? 'default');
                @endphp

                <div class="flex items-center gap-2">
                    <div class="rounded-full {{ $typeConfig['badge_class'] }} p-1.5">
                        <x-icon
                            name="{{ $typeConfig['icon'] }}"
                            class="h-4 w-4 {{ $typeConfig['icon_class'] }}"
                        />
                    </div>
{{--                    <x-badge--}}
{{--                        :text="$typeConfig['label']"--}}
{{--                        :color="$typeConfig['color']"--}}
{{--                        sm--}}
{{--                    />--}}
                </div>
                @endinteract

                {{-- Action Column --}}
                @interact('column_action', $row)
                @if($row->action)
                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            {{ $row->action }}
                        </span>
                @else
                    <span class="text-xs text-gray-400 dark:text-gray-600">-</span>
                @endif
                @endinteract

                {{-- Message Column with Tooltip --}}
                @interact('column_message', $row)
                @if($row->message)
                    <div
                        class="max-w-md truncate cursor-help"
                        title="{{ $row->message }}"
                        x-data
                        x-tooltip="'{{ addslashes($row->message) }}'"
                    >
                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                {{ Str::limit($row->message, 60) }}
                            </span>
                    </div>
                @else
                    <span class="text-xs text-gray-400 dark:text-gray-600">-</span>
                @endif
                @endinteract

                {{-- User Column with Avatar --}}
                @interact('column_user_id', $row)
                @if($row->user)
                    <div class="flex items-center gap-2">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-semibold">
                                {{ substr($row->user->name, 0, 2) }}
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $row->user->name }}
                            </p>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <x-icon name="cog" class="h-4 w-4 text-gray-500 dark:text-gray-400"/>
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('System') }}</span>
                    </div>
                @endif
                @endinteract

                {{-- IP Address Column --}}
                @interact('column_ip_address', $row)
                @if($row->ip_address)
                    <div class="flex items-center gap-1">
                        <x-icon name="globe-alt" class="h-3 w-3 text-gray-400"/>
                        <span class="text-xs font-mono text-gray-600 dark:text-gray-400">
                                {{ $row->ip_address }}
                            </span>
                    </div>
                @else
                    <span class="text-xs text-gray-400 dark:text-gray-600">-</span>
                @endif
                @endinteract

                {{-- Created At Column with Enhanced Display --}}
                @interact('column_created_at', $row)
                <div class="flex flex-col">
                        <span
                            class="text-xs font-medium text-gray-700 dark:text-gray-300"
                            title="{{ $row->created_at->format('Y-m-d H:i:s') }}"
                        >
                            {{ $row->created_at->diffForHumans() }}
                        </span>
                    <span class="text-xs text-gray-500 dark:text-gray-500">
                            {{ $row->created_at->format('M d, Y') }}
                        </span>
                </div>
                @endinteract

                {{-- Action Buttons Column --}}
                @interact('column_action_column', $row)
                <x-button.circle
                    icon="eye"
                    color="blue"
                    wire:click="$dispatchTo('logs.log-view', 'load::log', { 'log' : '{{ $row->id }}'})"
                    title="{{ __('View Details') }}"
                />
                {{--                    @can('delete_logs')--}}
                {{--                        <x-button.circle--}}
                {{--                            icon="trash"--}}
                {{--                            color="red"--}}
                {{--                            wire:click="deleteLog({{ $row->id }})"--}}
                {{--                            wire:confirm="{{ __('Are you sure you want to delete this log?') }}"--}}
                {{--                            title="{{ __('Delete Log') }}"--}}
                {{--                        />--}}
                {{--                    @endcan--}}
                @endinteract
            </x-table>
        @endif
    </x-card>

    {{-- Log View Modal Component --}}
    @if(isset($this->rows) && $this->rows->isNotEmpty())
        <livewire:logs.log-view :key="'log-view'"/>
    @endif
</div>
