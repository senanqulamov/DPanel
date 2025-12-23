<div class="space-y-6">
    {{-- Hero / Summary - Modern 2026 Design --}}
    <x-card class="bg-gradient-to-r from-red-600 via-red-500 to-pink-500 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <x-icon name="chart-bar" class="w-7 h-7" />
                    <h1 class="text-2xl md:text-3xl font-semibold tracking-tight">
                        {{ __('Admin Dashboard') }}
                    </h1>
                </div>
                <p class="text-sm md:text-base text-red-50/90 max-w-xl">
                    {{ __('Monitor system-wide activities, manage procurement operations, and analyze performance metrics.') }}
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full md:w-auto">
                <div class="bg-red-900/30 rounded-lg px-3 py-2 border border-red-400/40">
                    <p class="text-[10px] uppercase tracking-wide text-red-100/70 mb-1">{{ __('Total RFQs') }}</p>
                    <p class="text-lg font-semibold leading-tight">
                        {{ number_format($rfqStats['totalRfqs'] ?? 0) }}
                    </p>
                </div>
                <div class="bg-red-900/30 rounded-lg px-3 py-2 border border-red-400/40">
                    <p class="text-[10px] uppercase tracking-wide text-red-100/70 mb-1">{{ __('Open RFQs') }}</p>
                    <p class="text-lg font-semibold leading-tight">{{ $rfqStats['openRfqs'] ?? 0 }}</p>
                </div>
                <div class="bg-red-900/30 rounded-lg px-3 py-2 border border-red-400/40">
                    <p class="text-[10px] uppercase tracking-wide text-red-100/70 mb-1">{{ __('Total Quotes') }}</p>
                    <p class="text-lg font-semibold leading-tight">{{ $rfqStats['totalQuotes'] ?? 0 }}</p>
                </div>
                <div class="bg-red-900/30 rounded-lg px-3 py-2 border border-red-400/40">
                    <p class="text-[10px] uppercase tracking-wide text-red-100/70 mb-1">{{ __('Suppliers') }}</p>
                    <p class="text-lg font-semibold leading-tight">{{ $rfqStats['totalSuppliers'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </x-card>

    {{-- Procurement KPI Cards - 2026 Modern Design --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
        {{-- Open RFQs Card --}}
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500/10 via-blue-500/5 to-transparent dark:from-blue-500/20 dark:via-blue-500/10 backdrop-blur-sm border border-blue-200/50 dark:border-blue-500/30 hover:border-blue-400/60 dark:hover:border-blue-400/50 transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/20 dark:hover:shadow-blue-500/30">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-400/0 to-blue-600/0 group-hover:from-blue-400/5 group-hover:to-blue-600/5 transition-all duration-500"></div>
            <div class="relative p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1.5">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">{{ __('Open RFQs') }}</p>
                            @if(($rfqStats['openRfqs'] ?? 0) > 0)
                                <span class="px-1.5 py-0.5 text-[9px] font-bold rounded-full bg-blue-500 text-white">{{ $rfqStats['openRfqs'] }}</span>
                            @endif
                        </div>
                        <p class="text-2xl font-bold bg-gradient-to-br from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                            {{ $rfqStats['openRfqs'] ?? 0 }}
                        </p>
                    </div>
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-400 dark:to-blue-500 flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-300">
                        <x-icon name="document-text" class="w-6 h-6 text-white" />
                    </div>
                </div>
                <p class="text-[11px] leading-relaxed text-gray-600 dark:text-gray-400">
                    @if(($rfqStats['rfqsChange'] ?? 0) > 0)
                        <span class="text-green-600">↑ {{ abs($rfqStats['rfqsChange']) }}%</span>
                    @elseif(($rfqStats['rfqsChange'] ?? 0) < 0)
                        <span class="text-red-600">↓ {{ abs($rfqStats['rfqsChange']) }}%</span>
                    @else
                        <span class="text-gray-600">—</span>
                    @endif
                    {{ __(' vs last period') }}
                </p>
            </div>
        </div>

        {{-- Pending Quotes Card --}}
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500/10 via-amber-500/5 to-transparent dark:from-amber-500/20 dark:via-amber-500/10 backdrop-blur-sm border border-amber-200/50 dark:border-amber-500/30 hover:border-amber-400/60 dark:hover:border-amber-400/50 transition-all duration-300 hover:shadow-lg hover:shadow-amber-500/20 dark:hover:shadow-amber-500/30">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-400/0 to-amber-600/0 group-hover:from-amber-400/5 group-hover:to-amber-600/5 transition-all duration-500"></div>
            <div class="relative p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1.5">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">{{ __('Pending Quotes') }}</p>
                            @if(($rfqStats['pendingQuotes'] ?? 0) > 0)
                                <span class="px-1.5 py-0.5 text-[9px] font-bold rounded-full bg-amber-500 text-white animate-pulse">{{ $rfqStats['pendingQuotes'] }}</span>
                            @endif
                        </div>
                        <p class="text-2xl font-bold bg-gradient-to-br from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                            {{ $rfqStats['pendingQuotes'] ?? 0 }}
                        </p>
                    </div>
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 dark:from-amber-400 dark:to-amber-500 flex items-center justify-center shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform duration-300">
                        <x-icon name="clock" class="w-6 h-6 text-white" />
                    </div>
                </div>
                <p class="text-[11px] leading-relaxed text-gray-600 dark:text-gray-400">
                    @if(($rfqStats['quotesChange'] ?? 0) > 0)
                        <span class="text-green-600">↑ {{ abs($rfqStats['quotesChange']) }}%</span>
                    @elseif(($rfqStats['quotesChange'] ?? 0) < 0)
                        <span class="text-red-600">↓ {{ abs($rfqStats['quotesChange']) }}%</span>
                    @else
                        <span class="text-gray-600">—</span>
                    @endif
                    {{ __(' vs last period') }}
                </p>
            </div>
        </div>

        {{-- Awarded Contracts Card --}}
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-500/10 via-green-500/5 to-transparent dark:from-green-500/20 dark:via-green-500/10 backdrop-blur-sm border border-green-200/50 dark:border-green-500/30 hover:border-green-400/60 dark:hover:border-green-400/50 transition-all duration-300 hover:shadow-lg hover:shadow-green-500/20 dark:hover:shadow-green-500/30">
            <div class="absolute inset-0 bg-gradient-to-br from-green-400/0 to-green-600/0 group-hover:from-green-400/5 group-hover:to-green-600/5 transition-all duration-500"></div>
            <div class="relative p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1.5">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-green-600 dark:text-green-400">{{ __('Awarded') }}</p>
                        </div>
                        <p class="text-2xl font-bold bg-gradient-to-br from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                            {{ $rfqStats['awardedRfqs'] ?? 0 }}
                        </p>
                    </div>
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 dark:from-green-400 dark:to-green-500 flex items-center justify-center shadow-lg shadow-green-500/30 group-hover:scale-110 transition-transform duration-300">
                        <x-icon name="check-badge" class="w-6 h-6 text-white" />
                    </div>
                </div>
                <p class="text-[11px] leading-relaxed text-gray-600 dark:text-gray-400">
                    {{ __('Contracts successfully awarded') }}
                </p>
            </div>
        </div>

        {{-- Workflow Events Card --}}
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500/10 via-purple-500/5 to-transparent dark:from-purple-500/20 dark:via-purple-500/10 backdrop-blur-sm border border-purple-200/50 dark:border-purple-500/30 hover:border-purple-400/60 dark:hover:border-purple-400/50 transition-all duration-300 hover:shadow-lg hover:shadow-purple-500/20 dark:hover:shadow-purple-500/30">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-400/0 to-purple-600/0 group-hover:from-purple-400/5 group-hover:to-purple-600/5 transition-all duration-500"></div>
            <div class="relative p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1.5">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400">{{ __('Events Today') }}</p>
                            @if(($rfqStats['eventsToday'] ?? 0) > 0)
                                <span class="px-1.5 py-0.5 text-[9px] font-bold rounded-full bg-purple-500 text-white">{{ $rfqStats['eventsToday'] }}</span>
                            @endif
                        </div>
                        <p class="text-2xl font-bold bg-gradient-to-br from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                            {{ $rfqStats['eventsToday'] ?? 0 }}
                        </p>
                    </div>
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 dark:from-purple-400 dark:to-purple-500 flex items-center justify-center shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform duration-300">
                        <x-icon name="bolt" class="w-6 h-6 text-white" />
                    </div>
                </div>
                <p class="text-[11px] leading-relaxed text-gray-600 dark:text-gray-400">
                    {{ __('Total: ') }} {{ number_format($rfqStats['totalWorkflowEvents'] ?? 0) }}
                </p>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column (2/3) --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Charts Section - 2026 Design --}}
            <div class="relative overflow-hidden rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-gray-200/50 dark:border-slate-700/50 shadow-xl">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 via-transparent to-purple-500/5 dark:from-blue-500/10 dark:to-purple-500/10"></div>

                <div class="relative p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <x-icon name="chart-pie" class="w-5 h-5 text-white" />
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('RFQs by Status') }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Distribution overview') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        {{-- RFQs Chart --}}
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">{{ __('RFQs') }}</h4>
                            <div class="h-64">
                                <canvas id="rfqsChart"></canvas>
                                <div id="rfqsChartEmpty" class="text-center text-sm text-gray-400 py-6 hidden">{{ __('No RFQ data available') }}</div>
                            </div>
                        </div>

                        {{-- Quotes Chart --}}
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">{{ __('Quotes') }}</h4>
                            <div class="h-64">
                                <canvas id="quotesChart"></canvas>
                                <div id="quotesChartEmpty" class="text-center text-sm text-gray-400 py-6 hidden">{{ __('No Quote data available') }}</div>
                            </div>
                        </div>

                        {{-- Sales (Line) Chart --}}
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">{{ __('Sales (30 days)') }}</h4>
                            <div class="h-64">
                                <canvas id="salesChart"></canvas>
                                <div id="salesChartEmpty" class="text-center text-sm text-gray-400 py-6 hidden">{{ __('No Sales data available') }}</div>
                            </div>
                        </div>

                        {{-- Orders by Status (Doughnut) --}}
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">{{ __('Orders by Status') }}</h4>
                            <div class="h-64">
                                <canvas id="ordersChart"></canvas>
                                <div id="ordersChartEmpty" class="text-center text-sm text-gray-400 py-6 hidden">{{ __('No Order data available') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent RFQs - 2026 Design --}}
            <div class="relative overflow-hidden rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-gray-200/50 dark:border-slate-700/50 shadow-xl">
                <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 via-transparent to-emerald-500/5 dark:from-green-500/10 dark:to-emerald-500/10"></div>

                <div class="relative p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-lg shadow-green-500/30">
                                <x-icon name="document-text" class="w-5 h-5 text-white" />
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Recent RFQs') }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Latest procurement requests') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('monitoring.rfq.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium flex items-center gap-1 group">
                            {{ __('View All') }}
                            <x-icon name="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                        </a>
                    </div>

                    <div class="space-y-2">
                        @forelse($recentRfqs ?? [] as $rfq)
                            <div class="p-4 rounded-xl bg-gray-50 dark:bg-slate-800/50 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors border border-gray-200 dark:border-slate-700">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">{{ $rfq['title'] }}</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ __('By') }}: {{ $rfq['buyer'] }} • {{ $rfq['items_count'] }} {{ __('items') }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <x-badge
                                            :color="$rfq['status'] === 'open' ? 'green' : ($rfq['status'] === 'draft' ? 'yellow' : 'gray')"
                                            :text="ucfirst($rfq['status'])"
                                        />
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $rfq['created_at'] }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <x-icon name="document-text" class="w-12 h-12 text-gray-400 mx-auto mb-3" />
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No recent RFQs') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column (1/3) --}}
        <div class="space-y-6">
            {{-- Supplier Activity - 2026 Design --}}
            <div class="relative overflow-hidden rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-gray-200/50 dark:border-slate-700/50 shadow-xl">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 via-transparent to-pink-500/5 dark:from-purple-500/10 dark:to-pink-500/10"></div>

                <div class="relative p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-lg shadow-purple-500/30">
                            <x-icon name="users" class="w-5 h-5 text-white" />
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Supplier Activity') }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Engagement overview') }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="group relative overflow-hidden p-4 rounded-xl bg-gradient-to-br from-blue-500/10 to-blue-600/10 dark:from-blue-500/20 dark:to-blue-600/20 border border-blue-200/50 dark:border-blue-500/30 hover:border-blue-400/60 transition-all">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-1">{{ __('Total Suppliers') }}</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $rfqStats['totalSuppliers'] ?? 0 }}</p>
                                </div>
                                <x-icon name="building-office-2" class="w-8 h-8 text-blue-500 opacity-50" />
                            </div>
                        </div>

                        <div class="group relative overflow-hidden p-4 rounded-xl bg-gradient-to-br from-green-500/10 to-green-600/10 dark:from-green-500/20 dark:to-green-600/20 border border-green-200/50 dark:border-green-500/30 hover:border-green-400/60 transition-all">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-green-600 dark:text-green-400 uppercase tracking-wider mb-1">{{ __('Active Suppliers') }}</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $rfqStats['activeSuppliers'] ?? 0 }}</p>
                                </div>
                                <x-icon name="check-circle" class="w-8 h-8 text-green-500 opacity-50" />
                            </div>
                        </div>

                        <div class="group relative overflow-hidden p-4 rounded-xl bg-gradient-to-br from-amber-500/10 to-amber-600/10 dark:from-amber-500/20 dark:to-amber-600/20 border border-amber-200/50 dark:border-amber-500/30 hover:border-amber-400/60 transition-all">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wider mb-1">{{ __('Accepted Quotes') }}</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $rfqStats['acceptedQuotes'] ?? 0 }}</p>
                                </div>
                                <x-icon name="document-check" class="w-8 h-8 text-amber-500 opacity-50" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- System Health Card --}}
            <div class="relative overflow-hidden rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-gray-200/50 dark:border-slate-700/50 shadow-xl">
                <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 via-transparent to-pink-500/5 dark:from-red-500/10 dark:to-pink-500/10"></div>

                <div class="relative p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-pink-500 flex items-center justify-center shadow-lg shadow-red-500/30">
                            <x-icon name="heart" class="w-5 h-5 text-white" />
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('System Health') }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Operational overview') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $systemHealth['score'] ?? 0 }}%</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('Score based on recent errors and activity') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ __('Logs today') }}: <span class="font-semibold">{{ $systemHealth['logsToday'] ?? 0 }}</span></p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ __('Errors today') }}: <span class="font-semibold text-red-600">{{ $systemHealth['errorLogsToday'] ?? 0 }}</span></p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ __('Active users') }}: <span class="font-semibold">{{ $systemHealth['activeUsersToday'] ?? 0 }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top Products Card --}}
            <div class="relative overflow-hidden rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-gray-200/50 dark:border-slate-700/50 shadow-xl">
                <div class="relative p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-500 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                                <x-icon name="sparkles" class="w-5 h-5 text-white" />
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Top Products') }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('By number of orders') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @forelse($topProducts ?? [] as $product)
                            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-slate-800/50 border border-gray-200 dark:border-slate-700">
                                <div>
                                    <p class="font-semibold text-sm text-gray-900 dark:text-white">{{ $product['name'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($product['revenue'], 2) }} {{ __('revenue') }}</p>
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-300">{{ $product['orders'] }} {{ __('orders') }}</div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No product data') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Quick Actions & Recent Orders --}}
            <div class="grid grid-cols-1 gap-4">
                <div class="relative overflow-hidden rounded-2xl bg-white/80 dark:bg-slate-900/80 p-4 border border-gray-200/50 dark:border-slate-700/50 shadow-sm">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">{{ __('Quick Actions') }}</h4>

                    {{-- Action-card grid: uses route links where possible and Livewire emits for modals --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        {{-- Invite Supplier (Livewire emit - opens modal if Dashboard component listens) --}}
                        <button type="button" wire:click="$emit('inviteSupplier')" title="{{ __('Invite Supplier') }}" class="group flex items-center gap-3 p-3 rounded-lg border border-gray-200/50 dark:border-slate-700/50 bg-white/80 dark:bg-slate-900/80 hover:shadow-sm transition">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow">
                                <x-icon name="user-plus" class="w-5 h-5 text-white" />
                            </div>
                            <div class="text-left">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Invite Supplier') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Open supplier invite') }}</div>
                            </div>
                        </button>

                        {{-- Create RFQ (link to creation page) --}}
                        <a href="{{ route('rfq.create') }}" title="{{ __('Create RFQ') }}" class="group flex items-center gap-3 p-3 rounded-lg border border-gray-200/50 dark:border-slate-700/50 bg-white/80 dark:bg-slate-900/80 hover:shadow-sm transition">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow">
                                <x-icon name="document-plus" class="w-5 h-5 text-white" />
                            </div>
                            <div class="text-left">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Create RFQ') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Start a new RFQ') }}</div>
                            </div>
                        </a>

                        {{-- Export CSV (direct download route) --}}
                        <a href="{{ route('download.export') }}" title="{{ __('Export CSV') }}" class="group flex items-center gap-3 p-3 rounded-lg border border-gray-200/50 dark:border-slate-700/50 bg-white/80 dark:bg-slate-900/80 hover:shadow-sm transition">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow">
                                <x-icon name="arrow-down-tray" class="w-5 h-5 text-white" />
                            </div>
                            <div class="text-left">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Export CSV') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Download dashboard data') }}</div>
                            </div>
                        </a>

                        {{-- Manage Suppliers (quick link) --}}
                        <a href="{{ route('supplier.invitations.index') }}" title="{{ __('Manage Suppliers') }}" class="group flex items-center gap-3 p-3 rounded-lg border border-gray-200/50 dark:border-slate-700/50 bg-white/80 dark:bg-slate-900/80 hover:shadow-sm transition">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center shadow">
                                <x-icon name="users" class="w-5 h-5 text-white" />
                            </div>
                            <div class="text-left">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Manage Suppliers') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('View & invite suppliers') }}</div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-2xl bg-white/80 dark:bg-slate-900/80 p-4 border border-gray-200/50 dark:border-slate-700/50 shadow-sm">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Recent Orders') }}</h4>
                    <div class="space-y-2">
                        @forelse($recentOrders ?? [] as $order)
                            <div class="flex items-center justify-between p-2 rounded-md bg-gray-50 dark:bg-slate-800/50 border border-gray-100 dark:border-slate-700">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $order['order_number'] ?? '—' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order['product'] ?? '' }}</p>
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-300">{{ number_format($order['total'] ?? 0, 2) }}</div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No recent orders') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Recent Activity Timeline - 2026 Design --}}
            <div class="relative overflow-hidden rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-gray-200/50 dark:border-slate-700/50 shadow-xl">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 via-transparent to-orange-500/5 dark:from-amber-500/10 dark:to-orange-500/10"></div>

                <div class="relative p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/30">
                            <x-icon name="bolt" class="w-5 h-5 text-white" />
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Recent Activity') }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Latest workflow events') }}</p>
                        </div>
                    </div>

                    <div class="space-y-3 max-h-80 overflow-y-auto">
                        @forelse($workflowActivity ?? [] as $activity)
                            <div class="flex gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                        <x-icon name="bell" class="w-4 h-4 text-blue-600 dark:text-blue-400" />
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        <span class="font-semibold">{{ $activity['user'] }}</span>
                                        <span class="text-gray-600 dark:text-gray-400"> {{ $activity['description'] }}</span>
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $activity['occurred_at'] }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <x-icon name="bolt" class="w-12 h-12 text-gray-400 mx-auto mb-3" />
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No recent activity') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prepare chart registry so Livewire/partial re-renders won't leak charts
    window._dpanelCharts = window._dpanelCharts || {};

    const defaultOptions = {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: {
                position: 'bottom',
                labels: { padding: 12, boxWidth: 10, usePointStyle: true, pointStyle: 'circle', font: { size: 11 } }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.dataset.label || '';
                        const value = context.parsed !== undefined ? context.parsed : context.raw;
                        if (typeof value === 'number') {
                            return label ? (label + ': ' + value.toLocaleString()) : value.toLocaleString();
                        }
                        return label ? (label + ': ' + value) : value;
                    }
                }
            }
        }
    };

    function destroyChart(id) {
        const existing = window._dpanelCharts[id];
        if (existing && existing.destroy) {
            try { existing.destroy(); } catch (e) { /* ignore */ }
        }
        window._dpanelCharts[id] = null;
    }

    function createDoughnut(id, labels, data, colors) {
        const ctx = document.getElementById(id);
        if (!ctx) return null;
        destroyChart(id);
        if (!labels || labels.length === 0 || data.every(d => d === 0)) {
            document.getElementById(id + 'Empty')?.classList.remove('hidden');
            ctx.style.display = 'none';
            return null;
        }
        document.getElementById(id + 'Empty')?.classList.add('hidden');
        ctx.style.display = '';
        const cfg = {
            type: 'doughnut',
            data: { labels: labels, datasets: [{ data: data, backgroundColor: colors, borderWidth: 0 }] },
            options: Object.assign({}, defaultOptions, {
                cutout: '60%',
                onClick: (evt, elements) => {
                    if (elements && elements.length) {
                        const el = elements[0];
                        const idx = el.index;
                        const label = labels[idx];
                        // emit a DOM event so other JS or Livewire can react
                        document.dispatchEvent(new CustomEvent('dpanel:chartSegmentClick', { detail: { chartId: id, label, index: idx } }));
                    }
                }
            }),
        };
        window._dpanelCharts[id] = new Chart(ctx, cfg);
        return window._dpanelCharts[id];
    }

    function createLine(id, labels, datasets, yLabel) {
        const ctx = document.getElementById(id);
        if (!ctx) return null;
        destroyChart(id);
        if (!labels || labels.length === 0) {
            document.getElementById(id + 'Empty')?.classList.remove('hidden');
            ctx.style.display = 'none';
            return null;
        }
        document.getElementById(id + 'Empty')?.classList.add('hidden');
        ctx.style.display = '';
        const cfg = {
            type: 'line',
            data: { labels: labels, datasets: datasets },
            options: Object.assign({}, defaultOptions, {
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, ticks: { callback: v => typeof v === 'number' ? v.toLocaleString() : v } }
                }
            })
        };
        window._dpanelCharts[id] = new Chart(ctx, cfg);
        return window._dpanelCharts[id];
    }

    // Color palettes (kept intentionally simple and accessible)
    const palette = {
        blue: 'rgba(59,130,246,0.9)',
        green: 'rgba(34,197,94,0.9)',
        red: 'rgba(239,68,68,0.9)',
        amber: 'rgba(251,191,36,0.9)',
        gray: 'rgba(156,163,175,0.9)',
        purple: 'rgba(147,51,234,0.9)'
    };

    // Utility to safely extract labels/values from Laravel-provided arrays
    function arrLabels(dataArray) {
        try { return dataArray.map(i => i.label); } catch (e) { return []; }
    }
    function arrValues(dataArray) {
        try { return dataArray.map(i => Number(i.value || 0)); } catch (e) { return []; }
    }

    // Server-provided datasets (rendered by Blade into the page)
    const rfqsByStatus = @json($rfqsByStatus ?? []);
    const quotesByStatus = @json($quotesByStatus ?? []);
    const ordersByStatus = @json($ordersByStatus ?? []);
    const salesByDay = @json($salesByDay ?? []);

    // RFQs chart
    createDoughnut('rfqsChart', arrLabels(rfqsByStatus), arrValues(rfqsByStatus), [palette.blue, palette.green, palette.red, palette.amber, palette.gray]);

    // Quotes chart
    createDoughnut('quotesChart', arrLabels(quotesByStatus), arrValues(quotesByStatus), [palette.purple, palette.green, palette.red, palette.gray]);

    // Orders by status (doughnut)
    createDoughnut('ordersChart', arrLabels(ordersByStatus), arrValues(ordersByStatus), [palette.blue, palette.green, palette.amber, palette.gray, palette.purple]);

    // Sales by day (line)
    if (Array.isArray(salesByDay) && salesByDay.length > 0) {
        const salesLabels = salesByDay.map(d => d.date);
        const salesTotals = salesByDay.map(d => Number(d.total || 0));
        createLine('salesChart', salesLabels, [{ label: '{{ __('Total') }}', data: salesTotals, tension: 0.3, backgroundColor: 'rgba(59,130,246,0.08)', borderColor: palette.blue, pointRadius: 2, fill: true }], '{{ __('Amount') }}');
    } else {
        // ensure placeholder shown if no data
        document.getElementById('salesChartEmpty')?.classList.remove('hidden');
        document.getElementById('salesChart') && (document.getElementById('salesChart').style.display = 'none');
    }

    // Clean up charts on Livewire updates (if Livewire is present)
    if (window.Livewire) {
        Livewire.hook('message.processed', (el, component) => {
            // re-create charts after Livewire updates (they will be re-rendered server-side)
            setTimeout(() => {
                try {
                    createDoughnut('rfqsChart', arrLabels(@json($rfqsByStatus ?? [])), arrValues(@json($rfqsByStatus ?? [])), [palette.blue, palette.green, palette.red, palette.amber, palette.gray]);
                    createDoughnut('quotesChart', arrLabels(@json($quotesByStatus ?? [])), arrValues(@json($quotesByStatus ?? [])), [palette.purple, palette.green, palette.red, palette.gray]);
                    createDoughnut('ordersChart', arrLabels(@json($ordersByStatus ?? [])), arrValues(@json($ordersByStatus ?? [])), [palette.blue, palette.green, palette.amber, palette.gray, palette.purple]);
                    const sb = @json($salesByDay ?? []);
                    if (Array.isArray(sb) && sb.length > 0) {
                        createLine('salesChart', sb.map(d => d.date), sb.map(d => Number(d.total || 0)), '{{ __('Amount') }}');
                    }
                } catch (e) {
                    // swallow chart recreation errors to avoid breaking page
                    console.warn('Chart recreation failed', e);
                }
            }, 50);
        });
    }

    // Chart segment click handler - broadcast as Livewire event and console log
    document.addEventListener('dpanel:chartSegmentClick', function(e) {
        const detail = e.detail || {};
        console.log('Chart segment clicked', detail);
        if (window.Livewire && typeof Livewire.emit === 'function') {
            Livewire.emit('chartSegmentClicked', detail.chartId, detail.label, detail.index);
        }
    });

    // Quick action handlers removed: interactions are now handled via links or Livewire emits in the Blade.

});
</script>
@endpush
