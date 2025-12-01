<div>
    <x-card>
        <x-alert color="blue" icon="envelope">
            @lang('RFQ Invitations')
        </x-alert>

        <div class="mt-4 flex gap-2">
            <x-select.styled
                wire:model.live="statusFilter"
                :options="[
                    ['label' => 'All Statuses', 'value' => null],
                    ['label' => 'Pending', 'value' => 'pending'],
                    ['label' => 'Accepted', 'value' => 'accepted'],
                    ['label' => 'Declined', 'value' => 'declined'],
                    ['label' => 'Quoted', 'value' => 'quoted']
                ]"
                select="label:label|value:value"
            />
        </div>

        <x-table :$headers :$sort :rows="$this->rows" paginate :paginator="null" filter loading :quantity="[5, 10, 20, 'all']">
            @interact('column_id', $row)
            <x-badge text="#{{ $row->id }}" color="gray"/>
            @endinteract

            @interact('column_request_id', $row)
            <x-badge text="RFQ-{{ $row->request_id }}" color="blue"/>
            @endinteract

            @interact('column_title', $row)
            <div>
                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $row->request->title }}</div>
                <div class="text-xs text-gray-500">{{ Str::limit($row->request->description, 50) }}</div>
            </div>
            @endinteract

            @interact('column_status', $row)
            <x-badge
                :text="ucfirst($row->status)"
                :color="match($row->status) {
                    'pending' => 'yellow',
                    'accepted' => 'green',
                    'declined' => 'red',
                    'quoted' => 'blue',
                    default => 'gray'
                }"
            />
            @endinteract

            @interact('column_deadline', $row)
            @if($row->request->deadline)
                <div class="text-sm">
                    <div>{{ $row->request->deadline->format('M d, Y') }}</div>
                    <div class="text-xs text-gray-500">{{ $row->request->deadline->diffForHumans() }}</div>
                </div>
            @else
                -
            @endif
            @endinteract

            @interact('column_invited_at', $row)
            {{ $row->created_at->diffForHumans() }}
            @endinteract

            @interact('column_action', $row)
            <div class="flex gap-1">
                @if($row->status === 'pending')
                    <x-button.circle
                        icon="check"
                        color="green"
                        wire:click="acceptInvitation({{ $row->id }})"
                        title="{{ __('Accept Invitation') }}"
                    />
                    <x-button.circle
                        icon="x-mark"
                        color="red"
                        wire:click="declineInvitation({{ $row->id }})"
                        title="{{ __('Decline Invitation') }}"
                    />
                @endif
                @if($row->status === 'accepted' || $row->status === 'pending')
                    <x-button.circle
                        icon="document-plus"
                        color="blue"
                        onclick="window.location.href='{{ route('supplier.quotes.create', $row) }}'"
                        title="{{ __('Submit Quote') }}"
                    />
                @endif
                <x-button.circle
                    icon="eye"
                    wire:click="$dispatch('view-invitation', { id: {{ $row->id }} })"
                    title="{{ __('View Details') }}"
                />
            </div>
            @endinteract
        </x-table>
    </x-card>
</div>
