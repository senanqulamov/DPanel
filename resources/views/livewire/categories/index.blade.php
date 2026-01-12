<div>
    <x-card>
        <x-heading-title title="{{__('Categories')}}" icon="tag" padding="p-5" hover="-"/>

        <div class="mb-2 mt-4 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
            <livewire:categories.create @created="$refresh"/>
        </div>

        <x-table :$headers :$sort :rows="$this->rows" paginate :paginator="null" filter loading :quantity="[5, 10, 20, 'all']">
            @interact('column_id', $row)
            <span class="text-sm text-gray-900 dark:text-gray-100">#{{ $row->id }}</span>
            @endinteract

            @interact('column_name', $row)
            <a href="{{ route('categories.show', $row) }}" class="text-blue-600 hover:underline">
                <x-badge text="{{ $row->name }}" icon="eye" position="left"/>
            </a>
            @endinteract

            @interact('column_products_count', $row)
            <x-badge :text="$row->products_count . ' ' . __('products')" color="slate"/>
            @endinteract

            @interact('column_created_at', $row)
            <span class="text-sm text-gray-500">{{ optional($row->created_at)->diffForHumans() }}</span>
            @endinteract

            @interact('column_action', $row)
            <div class="flex gap-1">
                @can('edit_categories')
                    <x-button.circle icon="pencil" wire:click="$dispatch('load::category', { 'category' : '{{ $row->id }}'})"/>
                @endcan
                @can('delete_categories')
                    <livewire:categories.delete :category="$row" :key="uniqid('', true)" @deleted="$refresh"/>
                @endcan
            </div>
            @endinteract
        </x-table>
    </x-card>

    <livewire:categories.update @updated="$refresh"/>
</div>
