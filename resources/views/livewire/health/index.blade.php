<div>
    <x-card>
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">{{ __('System Health') }}</h3>
                <div class="flex gap-2">
                    <x-button wire:click="runChecks" sm>{{ __('Refresh') }}</x-button>
                    <x-badge color="primary" light>{{ __('Live') }}</x-badge>
                </div>
            </div>
        </x-slot>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-card>
                <x-slot name="header">{{ __('Application') }}</x-slot>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between items-center"><span>Env</span><span class="font-semibold">{{ $checks['app']['env'] }}</span></div>
                    <div class="flex justify-between items-center"><span>Debug</span><span class="font-semibold">{{ $checks['app']['debug'] ? 'on' : 'off' }}</span></div>
                    <div class="flex justify-between items-center"><span>Timezone</span><span class="font-semibold">{{ $checks['app']['timezone'] }}</span></div>
                </div>
            </x-card>

            <x-card>
                <x-slot name="header">{{ __('Database') }}</x-slot>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <span>{{ __('Connected') }}</span>
                        <x-badge :color="$checks['database']['connected'] ? 'green' : 'red'" light>
                            <x-icon :name="$checks['database']['connected'] ? 'check-circle' : 'x-circle'" class="w-4 h-4 mr-1" />
                            {{ $checks['database']['connected'] ? __('Yes') : __('No') }}
                        </x-badge>
                    </div>
                </div>
            </x-card>

            <x-card>
                <x-slot name="header">{{ __('Cache') }}</x-slot>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <span>{{ __('Working') }}</span>
                        <x-badge :color="$checks['cache']['connected'] ? 'green' : 'red'" light>
                            <x-icon :name="$checks['cache']['connected'] ? 'check-circle' : 'x-circle'" class="w-4 h-4 mr-1" />
                            {{ $checks['cache']['connected'] ? __('Yes') : __('No') }}
                        </x-badge>
                    </div>
                </div>
            </x-card>

            <x-card class="md:col-span-2">
                <x-slot name="header">{{ __('Queue') }}</x-slot>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between items-center"><span>{{ __('Connection') }}</span><span class="font-semibold">{{ $checks['queue']['connection'] }}</span></div>
                    <div class="flex justify-between items-center"><span>{{ __('Pending Jobs') }}</span><span class="font-semibold">{{ $checks['queue']['size'] }}</span></div>
                    <div class="flex justify-between items-center"><span>{{ __('Failed Jobs') }}</span><span class="font-semibold text-red-400">{{ $checks['queue']['failed'] }}</span></div>
                </div>
            </x-card>

            <x-card>
                <x-slot name="header">{{ __('Scheduler') }}</x-slot>
                <div class="text-sm">
                    <div class="flex justify-between items-center"><span>{{ __('Last Run') }}</span><span class="font-semibold">{{ $checks['scheduler']['last_run'] }}</span></div>
                </div>
            </x-card>
        </div>
        <div class="mt-6">
            <x-alert color="info" icon="information-circle">
                {{ __('Tip: Hover over each badge for more details. Health checks auto-refresh every 30 seconds.') }}
            </x-alert>
        </div>
    </x-card>
</div>
