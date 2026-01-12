<div>
    <x-card>
        <x-heading-title title="{{__('Field Workers')}}" icon="users" padding="p-5" hover="-"/>

        <div class="mb-4 mt-4 flex items-center justify-between">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('Manage field evaluators and worker suppliers assigned to your account.') }}
            </p>
            <livewire:supplier.workers.create @created="$refresh" />
        </div>

        <div class="overflow-x-auto">
            <x-table
                :headers="[
                    ['index' => 'id', 'label' => '#'],
                    ['index' => 'name', 'label' => __('Name')],
                    ['index' => 'email', 'label' => __('Email')],
                    ['index' => 'created_at', 'label' => __('Created')],
                    ['index' => 'action', 'label' => '', 'sortable' => false],
                ]"
                :rows="$workers"
                paginate
                :paginator="null"
            >
                @interact('column_id', $row)
                    <span class="text-sm text-gray-900 dark:text-gray-100">#{{ $row->id }}</span>
                @endinteract

                @interact('column_name', $row)
                    <div class="font-medium text-gray-900 dark:text-gray-100">{{ $row->name }}</div>
                @endinteract

                @interact('column_email', $row)
                    <div class="text-gray-600 dark:text-gray-300">{{ $row->email }}</div>
                @endinteract

                @interact('column_created_at', $row)
                    <span class="text-sm text-gray-500">{{ optional($row->created_at)->diffForHumans() }}</span>
                @endinteract

                @interact('column_action', $row)
                    <div class="flex gap-1">
                        <x-button.circle icon="pencil" wire:click="$dispatch('supplier::workers::load', { 'worker' : '{{ $row->id }}'})" />
                    </div>
                @endinteract
            </x-table>
        </div>
    </x-card>

    <livewire:supplier.workers.create @created="$refresh" />
    <livewire:supplier.workers.update @updated="$refresh" @deleted="$refresh" />
</div>
