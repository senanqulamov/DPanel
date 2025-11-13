<div class="space-y-6">
    <!-- Header with Title and Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">System Logs</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Monitor and track all system activities and events</p>
        </div>
        <div class="flex gap-3">
            <x-button icon="funnel" color="secondary" wire:click="toggleFilters">
                {{ $showFilters ? 'Hide' : 'Show' }} Filters
            </x-button>
            <x-button icon="arrow-down-tray" color="primary" wire:click="exportLogs">
                Export
            </x-button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @php
            $totalLogs = $this->rows->total();
            $todayLogs = \App\Models\Log::whereDate('created_at', today())->count();
            $errorLogs = \App\Models\Log::where('type', 'error')->whereDate('created_at', today())->count();
            $activeUsers = \App\Models\Log::whereDate('created_at', today())->distinct('user_id')->count('user_id');
        @endphp

        <x-card class="bg-gradient-to-br from-blue-500 to-blue-700 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-100">Total Logs</p>
                    <p class="mt-2 text-3xl font-bold">{{ number_format($totalLogs) }}</p>
                </div>
                <div class="rounded-full bg-white/20 p-3">
                    <x-icon name="clipboard-document-list" class="h-8 w-8" />
                </div>
            </div>
        </x-card>

        <x-card class="bg-gradient-to-br from-green-500 to-green-700 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-100">Today's Logs</p>
                    <p class="mt-2 text-3xl font-bold">{{ number_format($todayLogs) }}</p>
                </div>
                <div class="rounded-full bg-white/20 p-3">
                    <x-icon name="calendar" class="h-8 w-8" />
                </div>
            </div>
        </x-card>

        <x-card class="bg-gradient-to-br from-red-500 to-red-700 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-100">Errors Today</p>
                    <p class="mt-2 text-3xl font-bold">{{ number_format($errorLogs) }}</p>
                </div>
                <div class="rounded-full bg-white/20 p-3">
                    <x-icon name="exclamation-triangle" class="h-8 w-8" />
                </div>
            </div>
        </x-card>

        <x-card class="bg-gradient-to-br from-purple-500 to-purple-700 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-100">Active Users</p>
                    <p class="mt-2 text-3xl font-bold">{{ number_format($activeUsers) }}</p>
                </div>
                <div class="rounded-full bg-white/20 p-3">
                    <x-icon name="users" class="h-8 w-8" />
                </div>
            </div>
        </x-card>
    </div>

    <!-- Advanced Filters -->
    @if($showFilters)
    <x-card>
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Advanced Filters</h3>
                <x-button.circle icon="x-mark" color="red" size="sm" wire:click="toggleFilters" />
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
                    <select wire:model.live="typeFilter" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <option value="">All Types</option>
                        @foreach($this->logTypes as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- User Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">User</label>
                    <select wire:model.live="userFilter" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <option value="">All Users</option>
                        @foreach($this->users as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Model Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Model</label>
                    <select wire:model.live="modelFilter" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <option value="">All Models</option>
                        @foreach($this->models as $model)
                            <option value="{{ $model }}">{{ $model }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Action</label>
                    <select wire:model.live="actionFilter" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <option value="">All Actions</option>
                        @foreach($this->actions as $action)
                            <option value="{{ $action }}">{{ $action }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date From</label>
                    <input type="date" wire:model.live="dateFrom" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date To</label>
                    <input type="date" wire:model.live="dateTo" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                <x-button color="secondary" wire:click="clearFilters">
                    Clear Filters
                </x-button>
            </div>
        </div>
    </x-card>
    @endif

    <!-- Logs Table -->
    <x-card>
        <x-table :$headers :$sort :rows="$this->rows" paginate :paginator="null" filter loading :quantity="[10, 25, 50, 100, 'all']">
            @interact('column_id', $row)
            <span class="font-mono text-xs text-gray-500 dark:text-gray-400">#{{ $row->id }}</span>
            @endinteract

            @interact('column_type', $row)
            @php
                $typeConfig = match($row->type) {
                    'create' => ['color' => 'green', 'icon' => 'plus'],
                    'update' => ['color' => 'blue', 'icon' => 'pencil'],
                    'delete' => ['color' => 'red', 'icon' => 'trash'],
                    'page_view' => ['color' => 'purple', 'icon' => 'eye'],
                    'auth' => ['color' => 'yellow', 'icon' => 'lock-closed'],
                    'error' => ['color' => 'red', 'icon' => 'exclamation-triangle'],
                    'export' => ['color' => 'indigo', 'icon' => 'arrow-down-tray'],
                    'import' => ['color' => 'indigo', 'icon' => 'arrow-up-tray'],
                    'bulk' => ['color' => 'orange', 'icon' => 'square-3-stack-3d'],
                    'system' => ['color' => 'slate', 'icon' => 'cog'],
                    'security' => ['color' => 'pink', 'icon' => 'shield-check'],
                    'config' => ['color' => 'cyan', 'icon' => 'wrench'],
                    default => ['color' => 'gray', 'icon' => 'information-circle']
                };
            @endphp
            <div class="flex items-center gap-2">
                <div class="rounded-full bg-{{ $typeConfig['color'] }}-100 dark:bg-{{ $typeConfig['color'] }}-900 p-1.5">
                    <x-icon name="{{ $typeConfig['icon'] }}" class="h-4 w-4 text-{{ $typeConfig['color'] }}-600 dark:text-{{ $typeConfig['color'] }}-400" />
                </div>
                <x-badge :text="ucfirst($row->type)" :color="$typeConfig['color']" sm />
            </div>
            @endinteract

            @interact('column_user_id', $row)
            <div class="flex items-center gap-2">
                @if($row->user)
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-blue-400 to-blue-600 text-xs font-bold text-white">
                            {{ strtoupper(substr($row->user->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-gray-900 dark:text-white">{{ $row->user->name }}</p>
                            <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ $row->user->email }}</p>
                        </div>
                    </div>
                @else
                    <span class="text-sm text-gray-500 dark:text-gray-400 italic">System</span>
                @endif
            </div>
            @endinteract

            @interact('column_model', $row)
            @if($row->model)
                <div class="flex items-center gap-2">
                    <x-badge :text="$row->model" color="slate" sm />
                    @if($row->model_id)
                        <span class="text-xs text-gray-500 dark:text-gray-400">#{{ $row->model_id }}</span>
                    @endif
                </div>
            @else
                <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
            @endif
            @endinteract

            @interact('column_action', $row)
            @if($row->action)
                <code class="rounded bg-gray-100 dark:bg-gray-800 px-2 py-1 text-xs font-mono text-gray-700 dark:text-gray-300">
                    {{ $row->action }}
                </code>
            @else
                <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
            @endif
            @endinteract

            @interact('column_message', $row)
            <div class="max-w-md">
                <p class="truncate text-sm text-gray-900 dark:text-white" title="{{ $row->message }}">
                    {{ $row->message }}
                </p>
                @if($row->metadata && is_array($row->metadata))
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        {{ count($row->metadata) }} metadata fields
                    </p>
                @endif
            </div>
            @endinteract

            @interact('column_ip_address', $row)
            <div class="flex items-center gap-1">
                <x-icon name="globe-alt" class="h-4 w-4 text-gray-400" />
                <span class="font-mono text-xs text-gray-600 dark:text-gray-400">{{ $row->ip_address ?? 'N/A' }}</span>
            </div>
            @endinteract

            @interact('column_created_at', $row)
            <div class="text-sm">
                <p class="font-medium text-gray-900 dark:text-white">{{ $row->created_at->format('M d, Y') }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $row->created_at->format('H:i:s') }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $row->created_at->diffForHumans() }}</p>
            </div>
            @endinteract

            @interact('column_actions', $row)
            <div class="flex gap-1">
                <x-button.circle icon="eye" color="blue" size="sm" wire:click="viewLog({{ $row->id }})" title="View Details" />
                <x-button.circle icon="trash" color="red" size="sm" wire:click="confirmDelete({{ $row->id }})" title="Delete Log" />
            </div>
            @endinteract
        </x-table>
    </x-card>

    <!-- Log Details Modal -->
    <x-modal wire:model.live="showModal" title="Log Details" size="2xl">
        @if($this->selectedLog)
        <div class="space-y-6">
            <!-- Header Info -->
            <div class="rounded-lg bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 p-4">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3">
                            @php
                                $typeConfig = match($this->selectedLog->type) {
                                    'create' => ['color' => 'green', 'icon' => 'plus'],
                                    'update' => ['color' => 'blue', 'icon' => 'pencil'],
                                    'delete' => ['color' => 'red', 'icon' => 'trash'],
                                    'page_view' => ['color' => 'purple', 'icon' => 'eye'],
                                    'auth' => ['color' => 'yellow', 'icon' => 'lock-closed'],
                                    'error' => ['color' => 'red', 'icon' => 'exclamation-triangle'],
                                    'export' => ['color' => 'indigo', 'icon' => 'arrow-down-tray'],
                                    'import' => ['color' => 'indigo', 'icon' => 'arrow-up-tray'],
                                    'bulk' => ['color' => 'orange', 'icon' => 'square-3-stack-3d'],
                                    'system' => ['color' => 'slate', 'icon' => 'cog'],
                                    'security' => ['color' => 'pink', 'icon' => 'shield-check'],
                                    'config' => ['color' => 'cyan', 'icon' => 'wrench'],
                                    default => ['color' => 'gray', 'icon' => 'information-circle']
                                };
                            @endphp
                            <div class="rounded-full bg-{{ $typeConfig['color'] }}-500 p-2">
                                <x-icon name="{{ $typeConfig['icon'] }}" class="h-6 w-6 text-white" />
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $this->selectedLog->message }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Log ID: #{{ $this->selectedLog->id }}</p>
                            </div>
                        </div>
                    </div>
                    <x-badge :text="ucfirst($this->selectedLog->type)" :color="$typeConfig['color']" lg />
                </div>
            </div>

            <!-- Main Information Grid -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <!-- User -->
                <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <x-icon name="user" class="h-5 w-5 text-gray-400" />
                        <h4 class="font-semibold text-gray-900 dark:text-white">User</h4>
                    </div>
                    @if($this->selectedLog->user)
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-400 to-blue-600 text-sm font-bold text-white">
                                {{ strtoupper(substr($this->selectedLog->user->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $this->selectedLog->user->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $this->selectedLog->user->email }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 italic">System</p>
                    @endif
                </div>

                <!-- Date & Time -->
                <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <x-icon name="clock" class="h-5 w-5 text-gray-400" />
                        <h4 class="font-semibold text-gray-900 dark:text-white">Date & Time</h4>
                    </div>
                    <p class="text-gray-900 dark:text-white">{{ $this->selectedLog->created_at->format('F d, Y') }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $this->selectedLog->created_at->format('H:i:s') }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $this->selectedLog->created_at->diffForHumans() }}</p>
                </div>

                <!-- Model -->
                @if($this->selectedLog->model)
                <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <x-icon name="cube" class="h-5 w-5 text-gray-400" />
                        <h4 class="font-semibold text-gray-900 dark:text-white">Model</h4>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-badge :text="$this->selectedLog->model" color="slate" />
                        @if($this->selectedLog->model_id)
                            <span class="text-sm text-gray-500 dark:text-gray-400">ID: {{ $this->selectedLog->model_id }}</span>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Action -->
                @if($this->selectedLog->action)
                <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <x-icon name="bolt" class="h-5 w-5 text-gray-400" />
                        <h4 class="font-semibold text-gray-900 dark:text-white">Action</h4>
                    </div>
                    <code class="block rounded bg-gray-100 dark:bg-gray-800 px-3 py-2 text-sm font-mono text-gray-700 dark:text-gray-300">
                        {{ $this->selectedLog->action }}
                    </code>
                </div>
                @endif

                <!-- IP Address -->
                @if($this->selectedLog->ip_address)
                <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <x-icon name="globe-alt" class="h-5 w-5 text-gray-400" />
                        <h4 class="font-semibold text-gray-900 dark:text-white">IP Address</h4>
                    </div>
                    <code class="text-gray-900 dark:text-white font-mono">{{ $this->selectedLog->ip_address }}</code>
                </div>
                @endif

                <!-- User Agent -->
                @if($this->selectedLog->user_agent)
                <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 md:col-span-2">
                    <div class="flex items-center gap-2 mb-2">
                        <x-icon name="device-phone-mobile" class="h-5 w-5 text-gray-400" />
                        <h4 class="font-semibold text-gray-900 dark:text-white">User Agent</h4>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-300 break-all">{{ $this->selectedLog->user_agent }}</p>
                </div>
                @endif
            </div>

            <!-- Metadata Section -->
            @if($this->selectedLog->metadata && is_array($this->selectedLog->metadata) && count($this->selectedLog->metadata) > 0)
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center gap-2 mb-3">
                    <x-icon name="document-text" class="h-5 w-5 text-gray-400" />
                    <h4 class="font-semibold text-gray-900 dark:text-white">Metadata</h4>
                </div>
                <div class="space-y-2">
                    @foreach($this->selectedLog->metadata as $key => $value)
                        <div class="rounded bg-gray-50 dark:bg-gray-800 p-3">
                            <div class="flex items-start gap-3">
                                <span class="inline-flex items-center rounded bg-blue-100 dark:bg-blue-900 px-2 py-1 text-xs font-medium text-blue-800 dark:text-blue-200">
                                    {{ $key }}
                                </span>
                                <div class="flex-1 min-w-0">
                                    @if(is_array($value))
                                        <pre class="text-xs text-gray-700 dark:text-gray-300 overflow-x-auto">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                    @else
                                        <span class="text-sm text-gray-900 dark:text-white break-all">{{ $value }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <x-slot:footer>
            <div class="flex justify-between w-full">
                <x-button color="red" wire:click="closeModal">
                    Close
                </x-button>
            </div>
        </x-slot:footer>
        @endif
    </x-modal>
</div>
