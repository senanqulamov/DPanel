<div>
    <x-card>
        <x-slot name="header">
            <div class="flex items-center justify-between gap-5">
                <h3 class="text-lg font-semibold">{{ __('RFQ Monitoring') }}</h3>
                <x-badge color="primary" light>{{ __('Live Data') }}</x-badge>
            </div>
        </x-slot>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <x-kpi :title="__('Open')" :value="$metrics['open']" color="blue"/>
            <x-kpi :title="__('Draft')" :value="$metrics['draft']" color="slate"/>
            <x-kpi :title="__('Closed')" :value="$metrics['closed']" color="emerald"/>
            <x-kpi :title="__('Due in 3 days')" :value="$metrics['due_3_days']" color="amber"/>
            <x-kpi :title="__('Overdue')" :value="$metrics['overdue']" color="red"/>
        </div>

        {{-- Actions / RFQ controls: reuse existing rfq components for create/edit/delete --}}
        <div class="mb-6">
            <div class="flex items-center justify-end gap-3">
                @can('create_rfqs')
                    <livewire:monitoring.rfq.create @created="$refresh"/>
                @endcan
            </div>
        </div>

        <x-table :$headers :$sort :rows="$this->rows" paginate :paginator="null" filter loading :quantity="[5, 10, 20, 'all']">
            @interact('column_id', $row)
            {{ $row->id }}
            @endinteract

            @interact('column_title', $row)
            <a href="{{ route('monitoring.rfq.show', $row) }}" class="text-blue-600 hover:underline">
                <x-badge text="{{ $row->title }}" icon="eye" position="left"/>
            </a>
            @if($row->description)
                <div class="text-xs text-gray-500 truncate max-w-xs mt-1">
                    {{ $row->description }}
                </div>
            @endif
            @endinteract

            @interact('column_deadline', $row)
            {{ optional($row->deadline)->format('Y-m-d') ?? '—' }}
            @endinteract

            @interact('column_status', $row)
            <x-badge
                :color="$row->status === 'open' ? 'green' : ($row->status === 'closed' ? 'red' : 'gray')"
                :text="ucfirst($row->status)"
            />
            @endinteract

            @interact('column_items_count', $row)
            <button type="button" wire:click="$dispatch('monitoring::load::rfq_items', { rfq: '{{ $row->id }}' })" class="text-blue-600 transition hover:opacity-75 cursor-pointer">
                <x-badge :text="$row->items_count" icon="list-bullet"/>
            </button>
            @endinteract

            @interact('column_quotes_count', $row)
            <x-badge :text="$row->quotes_count" icon="document-duplicate"/>
            @endinteract

            @interact('column_created_at', $row)
            {{ $row->created_at->diffForHumans() }}
            @endinteract

            @interact('column_action', $row)
            <div class="flex gap-1">
                @can('edit_rfqs')
                    <x-button.circle icon="pencil" wire:click="$dispatch('monitoring::load::rfq', { rfq: '{{ $row->id }}' })"/>
                @endcan
                @can('delete_rfqs')
                    <livewire:monitoring.rfq.delete :rfq="$row" :key="uniqid('', true)" @deleted="$refresh"/>
                @endcan
            </div>
            @endinteract
        </x-table>
    </x-card>

    <livewire:monitoring.rfq.create @created="$refresh"/>
    <livewire:monitoring.rfq.update @updated="$refresh"/>
    <livewire:monitoring.rfq.items/>
</div>
