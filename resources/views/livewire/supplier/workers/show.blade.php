<div class="space-y-6">
    {{-- Header --}}
    <x-card class="bg-gradient-to-r from-purple-600 via-purple-500 to-indigo-600 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center text-2xl font-bold">
                    {{ strtoupper(substr($worker->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-xl font-bold">{{ $worker->name }}</h1>
                    <p class="text-sm text-purple-100">{{ $worker->email }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        @if($worker->is_active)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs bg-green-500/30 text-green-100 border border-green-400/40">
                                <span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span> {{ __('Active') }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs bg-red-500/30 text-red-100 border border-red-400/40">
                                <span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span> {{ __('Inactive') }}
                            </span>
                        @endif
                        <span class="text-xs text-purple-200">{{ __('Joined') }}: {{ $worker->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('supplier.workers.index') }}"
                   class="px-4 py-2 rounded-lg bg-white/20 hover:bg-white/30 text-white text-sm font-medium transition">
                    ← {{ __('Back to Workers') }}
                </a>
            </div>
        </div>
    </x-card>

    {{-- Tabs --}}
    <x-card>
        <div class="border-b border-gray-200 dark:border-slate-700 mb-6">
            <nav class="-mb-px flex gap-1">
                @foreach([
                    ['key' => 'rfqs',     'label' => __('Assigned RFQs'),   'icon' => 'clipboard-document-list'],
                    ['key' => 'messages', 'label' => __('Messages'),         'icon' => 'chat-bubble-left-right'],
                    ['key' => 'logs',     'label' => __('Activity Logs'),    'icon' => 'clock'],
                ] as $tab)
                    <button
                        wire:click="setTab('{{ $tab['key'] }}')"
                        class="flex items-center gap-2 px-4 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap
                               {{ $activeTab === $tab['key']
                                    ? 'border-purple-500 text-purple-600 dark:text-purple-400'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}"
                    >
                        <x-icon name="{{ $tab['icon'] }}" class="w-4 h-4" />
                        {{ $tab['label'] }}
                    </button>
                @endforeach
            </nav>
        </div>

        {{-- RFQs Tab --}}
        @if($activeTab === 'rfqs')
            <div class="space-y-8">

                {{-- ── Available RFQs ── --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                            <x-icon name="plus-circle" class="w-4 h-4 text-purple-500" />
                            {{ __('Available RFQs') }}
                            <span class="text-xs font-normal text-gray-400">({{ __('not yet assigned to this worker') }})</span>
                        </h3>
                    </div>

                    <div class="mb-3">
                        <x-input
                            wire:model.live.debounce.300ms="rfqSearch"
                            placeholder="{{ __('Search by title...') }}"
                            icon="magnifying-glass"
                        />
                    </div>

                    <x-table
                        :headers="[
                            ['index' => 'id',       'label' => '#',           'sortable' => false],
                            ['index' => 'title',    'label' => __('Title'),   'sortable' => false],
                            ['index' => 'buyer',    'label' => __('Buyer'),   'sortable' => false],
                            ['index' => 'status',   'label' => __('Status'),  'sortable' => false],
                            ['index' => 'deadline', 'label' => __('Deadline'),'sortable' => false],
                            ['index' => 'items',    'label' => __('Items'),   'sortable' => false],
                            ['index' => 'action',   'label' => '',            'sortable' => false],
                        ]"
                        :rows="$availableRfqs"
                        paginate
                        :paginator="null"
                    >
                        @interact('column_id', $row)
                            <span class="text-xs font-mono text-gray-500 dark:text-gray-400">#{{ $row->id }}</span>
                        @endinteract

                        @interact('column_title', $row)
                            <div class="font-medium text-gray-900 dark:text-gray-100 max-w-xs truncate">{{ $row->title }}</div>
                        @endinteract

                        @interact('column_buyer', $row)
                            <span class="text-sm text-gray-600 dark:text-gray-300">{{ $row->buyer->name ?? '—' }}</span>
                        @endinteract

                        @interact('column_status', $row)
                            <x-badge
                                :text="ucfirst(str_replace('_', ' ', $row->status))"
                                :color="match($row->status) {
                                    'open'       => 'green',
                                    'closed'     => 'red',
                                    'draft'      => 'gray',
                                    'in_progress'=> 'blue',
                                    default      => 'gray',
                                }"
                                sm
                            />
                        @endinteract

                        @interact('column_deadline', $row)
                            @if($row->deadline)
                                <span class="text-sm {{ $row->deadline->isPast() ? 'text-red-500' : 'text-gray-600 dark:text-gray-300' }}">
                                    {{ $row->deadline->format('M d, Y') }}
                                </span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        @endinteract

                        @interact('column_items', $row)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300">
                                {{ $row->items_count }}
                            </span>
                        @endinteract

                        @interact('column_action', $row)
                            <x-button
                                wire:click="confirmAssignRfq({{ $row->id }})"
                                wire:loading.attr="disabled"
                                wire:target="confirmAssignRfq({{ $row->id }})"
                                color="purple"
                                icon="plus"
                                sm
                            >
                                {{ __('Assign') }}
                            </x-button>
                        @endinteract
                    </x-table>
                </div>

                <hr class="border-gray-200 dark:border-slate-700" />

                {{-- ── Current Assignments ── --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                        <x-icon name="clipboard-document-check" class="w-4 h-4 text-purple-500" />
                        {{ __('Current Assignments') }}
                    </h3>

                    <x-table
                        :headers="[
                            ['index' => 'id',       'label' => '#',            'sortable' => false],
                            ['index' => 'title',    'label' => __('Title'),    'sortable' => false],
                            ['index' => 'buyer',    'label' => __('Buyer'),    'sortable' => false],
                            ['index' => 'items',    'label' => __('Items'),    'sortable' => false],
                            ['index' => 'assigned', 'label' => __('Assigned'), 'sortable' => false],
                            ['index' => 'status',   'label' => __('Status'),   'sortable' => false],
                            ['index' => 'action',   'label' => '',             'sortable' => false],
                        ]"
                        :rows="$workerAssignments"
                        paginate
                        :paginator="null"
                    >
                        @interact('column_id', $row)
                            <span class="text-xs font-mono text-gray-500 dark:text-gray-400">#{{ $row->request->id }}</span>
                        @endinteract

                        @interact('column_title', $row)
                            <div class="font-medium text-gray-900 dark:text-gray-100 max-w-xs truncate">
                                {{ $row->request->title }}
                            </div>
                        @endinteract

                        @interact('column_buyer', $row)
                            <span class="text-sm text-gray-600 dark:text-gray-300">{{ $row->request->buyer->name ?? '—' }}</span>
                        @endinteract

                        @interact('column_items', $row)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300">
                                {{ $row->request->items->count() }}
                            </span>
                        @endinteract

                        @interact('column_assigned', $row)
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ ($row->assigned_at ?? $row->created_at)->diffForHumans() }}
                            </span>
                        @endinteract

                        @interact('column_status', $row)
                            <x-select.styled
                                wire:model.live="assignmentStatuses.{{ $row->id }}"
                                :options="[
                                    ['label' => __('Pending'),     'value' => 'pending'],
                                    ['label' => __('In Progress'), 'value' => 'in_progress'],
                                    ['label' => __('Done'),        'value' => 'done'],
                                ]"
                                select="label:label|value:value"
                            />
                        @endinteract

                        @interact('column_action', $row)
                            <div class="flex items-center gap-1">
                                <x-button.circle
                                    icon="eye"
                                    color="indigo"
                                    href="{{ route('supplier.rfq.show', $row->request) }}"
                                    title="{{ __('View RFQ') }}"
                                    sm
                                />
                                <x-button.circle
                                    icon="trash"
                                    color="red"
                                    wire:click="confirmUnassignRfq({{ $row->id }})"
                                    sm
                                />
                            </div>
                        @endinteract
                    </x-table>
                </div>

            </div>
        @endif

        {{-- Messages Tab --}}
        @if($activeTab === 'messages')
            <div class="space-y-4">
                <div class="h-[400px] overflow-y-auto space-y-3 pr-1" id="messages-container">
                    @forelse($this->messages as $msg)
                        @php $isMine = $msg->sender_id === auth()->id(); @endphp
                        <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[75%] {{ $isMine ? 'bg-purple-600 text-white' : 'bg-gray-100 dark:bg-slate-700 text-gray-900 dark:text-gray-100' }} rounded-2xl px-4 py-2.5 shadow-sm">
                                @if(! $isMine)
                                    <p class="text-[10px] font-semibold text-purple-500 dark:text-purple-400 mb-1">{{ $msg->sender->name }}</p>
                                @endif
                                <p class="text-sm leading-relaxed">{{ $msg->body }}</p>
                                @if($msg->rfq)
                                    <p class="text-[10px] mt-1 {{ $isMine ? 'text-purple-200' : 'text-gray-500 dark:text-gray-400' }}">
                                        {{ __('Re') }}: {{ $msg->rfq->title }}
                                    </p>
                                @endif
                                <p class="text-[10px] mt-1 {{ $isMine ? 'text-purple-200' : 'text-gray-400 dark:text-gray-500' }} text-right">
                                    {{ $msg->created_at->format('M d, H:i') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center">
                                <x-icon name="chat-bubble-left-right" class="w-10 h-10 text-gray-300 dark:text-slate-600 mx-auto mb-2" />
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No messages yet. Start the conversation!') }}</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="border-t border-gray-200 dark:border-slate-700 pt-4">
                    <form wire:submit="sendMessage" class="flex gap-3">
                        <div class="flex-1">
                            <x-textarea
                                wire:model="newMessage"
                                placeholder="{{ __('Type your message...') }}"
                                rows="2"
                            />
                        </div>
                        <div class="flex items-end">
                            <x-button type="submit" color="purple" icon="paper-airplane">
                                {{ __('Send') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                document.addEventListener('livewire:initialized', () => {
                    const scrollToBottom = () => {
                        const el = document.getElementById('messages-container');
                        if (el) el.scrollTop = el.scrollHeight;
                    };
                    scrollToBottom();
                    Livewire.on('message-sent', scrollToBottom);
                });
            </script>
        @endif

        {{-- Logs Tab --}}
        @if($activeTab === 'logs')
            <div class="space-y-5">

                {{-- Stats Row --}}
                @php $stats = $this->logStats; @endphp
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="rounded-xl p-3 bg-gray-50 dark:bg-slate-800/60 border border-gray-200/60 dark:border-slate-700/40 text-center">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">{{ __('Total') }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total']) }}</p>
                    </div>
                    <div class="rounded-xl p-3 bg-purple-50 dark:bg-purple-900/20 border border-purple-200/60 dark:border-purple-700/40 text-center">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-purple-600 dark:text-purple-400 mb-1">{{ __('Today') }}</p>
                        <p class="text-2xl font-bold text-purple-700 dark:text-purple-300">{{ $stats['today'] }}</p>
                    </div>
                    <div class="rounded-xl p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200/60 dark:border-blue-700/40 text-center">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-blue-600 dark:text-blue-400 mb-1">{{ __('Last 7 Days') }}</p>
                        <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $stats['week'] }}</p>
                    </div>
                    <div class="rounded-xl p-3 {{ $stats['errors'] > 0 ? 'bg-red-50 dark:bg-red-900/20 border-red-200/60 dark:border-red-700/40' : 'bg-gray-50 dark:bg-slate-800/60 border-gray-200/60 dark:border-slate-700/40' }} border text-center">
                        <p class="text-[10px] font-bold uppercase tracking-wide {{ $stats['errors'] > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }} mb-1">{{ __('Errors') }}</p>
                        <p class="text-2xl font-bold {{ $stats['errors'] > 0 ? 'text-red-700 dark:text-red-300' : 'text-gray-700 dark:text-gray-300' }}">{{ $stats['errors'] }}</p>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="flex flex-wrap items-end gap-3">
                    <div class="flex-1 min-w-[180px]">
                        <x-input
                            wire:model.live.debounce.300ms="logSearch"
                            placeholder="{{ __('Search logs...') }}"
                            icon="magnifying-glass"
                        />
                    </div>
                    @if(count($this->logTypes) > 0)
                        <div class="min-w-[160px]">
                            <x-select.styled
                                wire:model.live="logTypeFilter"
                                :options="collect($this->logTypes)->map(fn($t) => ['label' => ucfirst(str_replace('_', ' ', $t)), 'value' => $t])->toArray()"
                                select="label:label|value:value"
                                :placeholder="__('All Types')"
                            />
                        </div>
                    @endif
                    @if($logSearch !== null || $logTypeFilter !== null)
                        <x-button wire:click="clearLogFilters" color="red" icon="x-mark" sm>
                            {{ __('Clear') }}
                        </x-button>
                    @endif
                </div>

                {{-- Log List --}}
                @if($this->workerLogs->isEmpty())
                    <div class="text-center py-12 border border-dashed border-gray-300 dark:border-slate-600 rounded-xl">
                        <x-icon name="clock" class="w-10 h-10 text-gray-300 dark:text-slate-600 mx-auto mb-2" />
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ ($logSearch !== null || $logTypeFilter !== null) ? __('No activity matches your filters.') : __('No activity recorded for this worker yet.') }}
                        </p>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach($this->workerLogs as $log)
                            @php
                                // Route name → friendly page name map
                                $pageNames = [
                                    'supplier.field.dashboard'        => __('Dashboard'),
                                    'supplier.field.rfq.index'        => __('My Assigned RFQs'),
                                    'supplier.field.rfq.show'         => __('RFQ Details'),
                                    'supplier.field.messages.index'   => __('Messages'),
                                    'supplier.field.logs.index'       => __('Activity Log'),
                                    'supplier.dashboard'              => __('Supplier Dashboard'),
                                    'supplier.rfq.index'              => __('RFQ List'),
                                    'supplier.rfq.show'               => __('RFQ Details'),
                                    'supplier.quotes.index'           => __('My Quotes'),
                                    'supplier.invitations.index'      => __('Invitations'),
                                    'supplier.products.index'         => __('Products'),
                                    'supplier.orders.index'           => __('Orders'),
                                    'supplier.messages.index'         => __('Messages'),
                                    'supplier.workers.index'          => __('Field Workers'),
                                    'supplier.workers.show'           => __('Worker Profile'),
                                    'supplier.logs.index'             => __('Activity Log'),
                                    'login'                           => __('Login Page'),
                                ];

                                $meta       = $log->metadata ?? [];
                                $routeName  = $meta['route'] ?? null;
                                $pageUrl    = $meta['url'] ?? null;
                                $pageName   = $routeName ? ($pageNames[$routeName] ?? ucwords(str_replace(['.', '_'], ' ', last(explode('.', $routeName))))) : null;

                                // Build friendly description + subtitle
                                [$friendlyDesc, $friendlySub] = match(true) {
                                    $log->type === 'login'
                                        => [__('Signed in to their account'), null],

                                    $log->type === 'logout'
                                        => [__('Signed out of their account'), null],

                                    $log->type === 'page_view' && $pageName !== null
                                        => [
                                            __('Visited: :page', ['page' => $pageName]),
                                            $pageUrl ? parse_url($pageUrl, PHP_URL_PATH) : null,
                                        ],

                                    $log->type === 'page_view'
                                        => [__('Visited a page'), $pageUrl ? parse_url($pageUrl, PHP_URL_PATH) : null],

                                    $log->type === 'create' && $log->model
                                        => [__('Created a new :model', ['model' => strtolower($log->model)]), $log->model_id ? '#' . $log->model_id : null],

                                    $log->type === 'update' && $log->model
                                        => [__('Updated a :model', ['model' => strtolower($log->model)]), $log->model_id ? '#' . $log->model_id : null],

                                    $log->type === 'delete' && $log->model
                                        => [__('Deleted a :model', ['model' => strtolower($log->model)]), $log->model_id ? '#' . $log->model_id : null],

                                    $log->type === 'export'
                                        => [__('Exported data'), isset($meta['format']) ? strtoupper($meta['format']) . (isset($meta['record_count']) ? ' · ' . $meta['record_count'] . ' records' : '') : null],

                                    $log->type === 'import'
                                        => [__('Imported data'), isset($meta['success_count']) ? $meta['success_count'] . ' imported' . (($meta['failure_count'] ?? 0) > 0 ? ', ' . $meta['failure_count'] . ' failed' : '') : null],

                                    $log->type === 'error'
                                        => [__('An error occurred'), isset($meta['message']) ? $meta['message'] : null],

                                    $log->type === 'security'
                                        => [__('Security event detected'), null],

                                    default
                                        => [$log->message ?? __('Performed an action'), null],
                                };

                                $iconConfig = match($log->type) {
                                    'login'     => ['icon' => 'arrow-right-on-rectangle', 'bg' => 'bg-green-100 dark:bg-green-900/30',  'text' => 'text-green-600 dark:text-green-400'],
                                    'logout'    => ['icon' => 'arrow-left-on-rectangle',  'bg' => 'bg-orange-100 dark:bg-orange-900/30','text' => 'text-orange-600 dark:text-orange-400'],
                                    'page_view' => ['icon' => 'eye',                       'bg' => 'bg-gray-100 dark:bg-slate-700/50',   'text' => 'text-gray-500 dark:text-gray-400'],
                                    'create'    => ['icon' => 'plus-circle',               'bg' => 'bg-green-100 dark:bg-green-900/30',  'text' => 'text-green-600 dark:text-green-400'],
                                    'update'    => ['icon' => 'pencil-square',             'bg' => 'bg-blue-100 dark:bg-blue-900/30',    'text' => 'text-blue-600 dark:text-blue-400'],
                                    'delete'    => ['icon' => 'trash',                     'bg' => 'bg-red-100 dark:bg-red-900/30',      'text' => 'text-red-600 dark:text-red-400'],
                                    'export'    => ['icon' => 'arrow-down-tray',           'bg' => 'bg-indigo-100 dark:bg-indigo-900/30','text' => 'text-indigo-600 dark:text-indigo-400'],
                                    'import'    => ['icon' => 'arrow-up-tray',             'bg' => 'bg-indigo-100 dark:bg-indigo-900/30','text' => 'text-indigo-600 dark:text-indigo-400'],
                                    'error'     => ['icon' => 'exclamation-circle',        'bg' => 'bg-red-100 dark:bg-red-900/30',      'text' => 'text-red-600 dark:text-red-400'],
                                    'security'  => ['icon' => 'shield-exclamation',        'bg' => 'bg-red-100 dark:bg-red-900/30',      'text' => 'text-red-600 dark:text-red-400'],
                                    default     => ['icon' => 'information-circle',        'bg' => 'bg-purple-100 dark:bg-purple-900/30','text' => 'text-purple-600 dark:text-purple-400'],
                                };
                            @endphp
                            <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-slate-700/50 hover:bg-gray-50 dark:hover:bg-slate-800/40 transition-colors">
                                <div class="flex-shrink-0 w-9 h-9 rounded-lg {{ $iconConfig['bg'] }} flex items-center justify-center">
                                    <x-icon name="{{ $iconConfig['icon'] }}" class="w-4 h-4 {{ $iconConfig['text'] }}" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $friendlyDesc }}</p>
                                    @if($friendlySub)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-mono truncate mt-0.5">{{ $friendlySub }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5 flex items-center gap-1.5">
                                        <x-icon name="clock" class="w-3 h-3" />
                                        {{ $log->created_at->format('M d, Y · H:i') }}
                                        &bull; {{ $log->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 flex items-center justify-between gap-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ __('Showing') }} {{ $this->workerLogs->firstItem() }}–{{ $this->workerLogs->lastItem() }} {{ __('of') }} {{ number_format($this->workerLogs->total()) }} {{ __('activities') }}
                        </p>
                        {{ $this->workerLogs->links() }}
                    </div>
                @endif
            </div>
        @endif
    </x-card>
</div>
