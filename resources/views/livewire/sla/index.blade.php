<div>
    <x-card>
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">{{ __('SLA Tracker') }}</h3>
                <x-button wire:click="dispatchReminders" sm>{{ __('Send Reminders Now') }}</x-button>
            </div>
        </x-slot>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <x-kpi :title="__('Open RFQs')" :value="$overview['open']" color="blue" />
            <x-kpi :title="__('Due in 7 days')" :value="$overview['due_7']" color="indigo" />
            <x-kpi :title="__('Due in 3 days')" :value="$overview['due_3']" color="amber" />
            <x-kpi :title="__('Due in 24 hours')" :value="$overview['due_1']" color="rose" />
            <x-kpi :title="__('Overdue')" :value="$overview['overdue']" color="red" />
        </div>

        <div class="space-y-4">
            <x-alert icon="information-circle" shadow>
                Reminders are sent at 7, 3, and 1 day before deadlines. Use the button above to trigger a manual run.
            </x-alert>
        </div>
    </x-card>
</div>
