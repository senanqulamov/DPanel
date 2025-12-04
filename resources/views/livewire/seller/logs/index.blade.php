<div class="space-y-4">
    <x-seller.nav/>

    <div>
        <x-card>
            <x-alert color="black" icon="clipboard-document-list">
                @lang('My Activity Log')
            </x-alert>

            <div class="mb-4 mt-4 flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-[200px]">
                    <x-select.styled
                        label="{{ __('Filter by Type') }}"
                        wire:model.live="typeFilter"
                        :options="collect($this->logTypes)->map(fn($type) => ['label' => ucfirst($type), 'value' => $type])->toArray()"
                        select="label:label|value:value"
                    />
                </div>
                @if($typeFilter)
                    <x-button color="red" text="{{ __('Clear Filter') }}" wire:click="clearTypeFilter"/>
                @endif
            </div>

            <x-table :$headers :$sort :rows="$this->rows" paginate :paginator="null" filter loading :quantity="[5, 10, 20, 50, 'all']">
                @interact('column_type', $row)
                <x-badge :text="ucfirst($row->type)" color="blue" sm/>
                @endinteract

                @interact('column_action', $row)
                <span class="text-xs text-gray-600 dark:text-gray-400">{{ $row->action ?? '-' }}</span>
                @endinteract

                @interact('column_message', $row)
                <div class="max-w-xs truncate" title="{{ $row->message }}">
                    {{ Str::limit($row->message, 50) }}
                </div>
                @endinteract

                @interact('column_created_at', $row)
                <span class="text-xs" title="{{ $row->created_at->format('Y-m-d H:i:s') }}">
                    {{ $row->created_at->diffForHumans() }}
                </span>
                @endinteract
            </x-table>
        </x-card>
    </div>
</div>
