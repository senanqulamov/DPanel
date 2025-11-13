<x-modal wire:model="modal" title="Log Details" size="2xl">
    @if($log)
    <div class="space-y-6">
        <!-- Header Info -->
        <div class="rounded-lg bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 p-4">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3">
                        @php
                            $typeConfig = match($log->type) {
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
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $log->message }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Log ID: #{{ $log->id }}</p>
                        </div>
                    </div>
                </div>
                <x-badge :text="ucfirst($log->type)" :color="$typeConfig['color']" lg />
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
                @if($log->user)
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-400 to-blue-600 text-sm font-bold text-white">
                            {{ strtoupper(substr($log->user->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $log->user->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $log->user->email }}</p>
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
                <p class="text-gray-900 dark:text-white">{{ $log->created_at->format('F d, Y') }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $log->created_at->format('H:i:s') }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $log->created_at->diffForHumans() }}</p>
            </div>

            <!-- Model -->
            @if($log->model)
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center gap-2 mb-2">
                    <x-icon name="cube" class="h-5 w-5 text-gray-400" />
                    <h4 class="font-semibold text-gray-900 dark:text-white">Model</h4>
                </div>
                <div class="flex items-center gap-2">
                    <x-badge :text="$log->model" color="slate" />
                    @if($log->model_id)
                        <span class="text-sm text-gray-500 dark:text-gray-400">ID: {{ $log->model_id }}</span>
                    @endif
                </div>
            </div>
            @endif

            <!-- Action -->
            @if($log->action)
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center gap-2 mb-2">
                    <x-icon name="bolt" class="h-5 w-5 text-gray-400" />
                    <h4 class="font-semibold text-gray-900 dark:text-white">Action</h4>
                </div>
                <code class="block rounded bg-gray-100 dark:bg-gray-800 px-3 py-2 text-sm font-mono text-gray-700 dark:text-gray-300">
                    {{ $log->action }}
                </code>
            </div>
            @endif

            <!-- IP Address -->
            @if($log->ip_address)
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center gap-2 mb-2">
                    <x-icon name="globe-alt" class="h-5 w-5 text-gray-400" />
                    <h4 class="font-semibold text-gray-900 dark:text-white">IP Address</h4>
                </div>
                <code class="text-gray-900 dark:text-white font-mono">{{ $log->ip_address }}</code>
            </div>
            @endif

            <!-- User Agent -->
            @if($log->user_agent)
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 md:col-span-2">
                <div class="flex items-center gap-2 mb-2">
                    <x-icon name="device-phone-mobile" class="h-5 w-5 text-gray-400" />
                    <h4 class="font-semibold text-gray-900 dark:text-white">User Agent</h4>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-300 break-all">{{ $log->user_agent }}</p>
            </div>
            @endif
        </div>

        <!-- Metadata Section -->
        @if($log->metadata && is_array($log->metadata) && count($log->metadata) > 0)
        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center gap-2 mb-3">
                <x-icon name="document-text" class="h-5 w-5 text-gray-400" />
                <h4 class="font-semibold text-gray-900 dark:text-white">Metadata</h4>
            </div>
            <div class="space-y-2">
                @foreach($log->metadata as $key => $value)
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
            <x-button color="red" wire:click="close">
                Close
            </x-button>
            <div class="flex gap-2">
                <x-button color="secondary" icon="clipboard" onclick="navigator.clipboard.writeText('{{ json_encode($log->toArray()) }}')">
                    Copy JSON
                </x-button>
            </div>
        </div>
    </x-slot:footer>
    @endif
</x-modal>
