<div>
    <x-card>
        <x-alert color="black" icon="clipboard-document-list">
            @lang('System Logs')
        </x-alert>

        <x-table :$headers :$sort :rows="$this->rows" paginate :paginator="null" filter loading :quantity="[5, 10, 20, 'all']" class="mt-4">
            @interact('column_type', $row)
            @php
                $color = match($row->type) {
                    'info' => 'blue',
                    'warning' => 'yellow',
                    'error' => 'red',
                    'success' => 'green',
                    default => 'gray'
                };
            @endphp
            <x-badge :text="ucfirst($row->type)" :color="$color" />
            @endinteract

            @interact('column_message', $row)
            <div class="max-w-md truncate" title="{{ $row->message }}">
                {{ $row->message }}
            </div>
            @endinteract

            @interact('column_created_at', $row)
            {{ $row->created_at->diffForHumans() }}
            @endinteract

            @interact('column_action', $row)
            <div class="flex gap-1">
                <livewire:logs.delete :log="$row" :key="uniqid('', true)" @deleted="$refresh"/>
            </div>
            @endinteract
        </x-table>
    </x-card>
</div>
