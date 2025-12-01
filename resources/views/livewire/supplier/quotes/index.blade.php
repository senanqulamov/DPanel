<div>
    <x-card>
        <x-alert color="green" icon="document-text">
            @lang('My Quotes')
        </x-alert>

        <div class="mt-4 flex gap-2">
            <x-select.styled
                wire:model.live="statusFilter"
                :options="[
                    ['label' => 'All Statuses', 'value' => null],
                    ['label' => 'Draft', 'value' => 'draft'],
                    ['label' => 'Submitted', 'value' => 'submitted'],
                    ['label' => 'Under Review', 'value' => 'under_review'],
                    ['label' => 'Won', 'value' => 'won'],
                    ['label' => 'Lost', 'value' => 'lost'],
                    ['label' => 'Withdrawn', 'value' => 'withdrawn']
                ]"
                select="label:label|value:value"
            />
        </div>

        <x-table :$headers :$sort :rows="$this->rows" paginate :paginator="null" filter loading :quantity="[5, 10, 20, 'all']">
            @interact('column_id', $row)
            <x-badge text="#{{ $row->id }}" color="gray"/>
            @endinteract

            @interact('column_request', $row)
            <div>
                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $row->request->title }}</div>
                <div class="text-xs text-gray-500">RFQ #{{ $row->request_id }}</div>
            </div>
            @endinteract

            @interact('column_total_amount', $row)
            <div class="font-medium">{{ $row->currency }} ${{ number_format($row->total_amount, 2) }}</div>
            @endinteract

            @interact('column_status', $row)
            <x-badge
                :text="ucfirst(str_replace('_', ' ', $row->status))"
                :color="match($row->status) {
                    'draft' => 'gray',
                    'submitted' => 'blue',
                    'under_review' => 'yellow',
                    'won' => 'green',
                    'lost' => 'red',
                    'withdrawn' => 'orange',
                    default => 'gray'
                }"
            />
            @endinteract

            @interact('column_valid_until', $row)
            @if($row->valid_until)
                <div class="text-sm">
                    <div>{{ $row->valid_until->format('M d, Y') }}</div>
                    @if($row->valid_until->isPast())
                        <div class="text-xs text-red-500">{{ __('Expired') }}</div>
                    @else
                        <div class="text-xs text-gray-500">{{ $row->valid_until->diffForHumans() }}</div>
                    @endif
                </div>
            @else
                -
            @endif
            @endinteract

            @interact('column_submitted_at', $row)
            @if($row->submitted_at)
                {{ $row->submitted_at->diffForHumans() }}
            @else
                <x-badge text="Draft" color="gray"/>
            @endif
            @endinteract

            @interact('column_action', $row)
            <div class="flex gap-1">
                <x-button.circle
                    icon="eye"
                    wire:click="$dispatch('view-quote', { id: {{ $row->id }} })"
                    title="{{ __('View Details') }}"
                />
                @if($row->status === 'draft')
                    <x-button.circle
                        icon="pencil"
                        color="blue"
                        title="{{ __('Edit Quote') }}"
                    />
                @endif
            </div>
            @endinteract
        </x-table>
    </x-card>
</div>
