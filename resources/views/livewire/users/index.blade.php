<div>
    <x-card>
        <x-alert color="black" icon="light-bulb">
            @lang('Users')
        </x-alert>

        <div class="mb-2 mt-4">
            <livewire:users.create @created="$refresh"/>
        </div>

        <x-table :$headers :$sort :rows="$this->rows" paginate :paginator="null" filter loading :quantity="[5, 10, 20, 'all']">
            @interact('column_name', $row)
            <a href="{{ route('users.show', $row) }}" class="text-blue-600 hover:underline">
                <x-badge text="{{ $row->name }}" icon="eye" position="left"/>
            </a>
            @endinteract

            @interact('column_email', $row)
            {{ $row->email }}
            @endinteract

            @interact('column_company_name', $row)
            {{ $row->company_name ?? '-' }}
            @endinteract

            @interact('column_roles', $row)
            <div class="flex gap-1 flex-wrap">
                @if($row->is_buyer)
                    <x-badge color="blue" text="Buyer" sm />
                @endif
                @if($row->is_seller)
                    <x-badge color="green" text="Seller" sm />
                    @if($row->markets->isNotEmpty())
                        <div class="text-xs text-gray-600 dark:text-gray-400 w-full mt-1">
                            Markets: {{ $row->markets->pluck('name')->join(', ') }}
                        </div>
                    @endif
                @endif
                @if($row->is_supplier)
                    @if($row->supplier_status === 'active')
                        <x-badge color="purple" text="Supplier" sm />
                    @else
                        <x-badge color="slate" text="Supplier ({{ $row->supplier_status }})" sm />
                    @endif
                @endif
            </div>
            @endinteract

            @interact('column_created_at', $row)
            {{ $row->created_at->diffForHumans() }}
            @endinteract

            @interact('column_action', $row)
            <div class="flex gap-1">
                @can('edit_users')
                    <x-button.circle icon="pencil" wire:click="$dispatch('load::user', { 'user' : '{{ $row->id }}'})"/>
                @endcan
                @can('delete_users')
                    <livewire:users.delete :user="$row" :key="uniqid('', true)" @deleted="$refresh"/>
                @endcan
            </div>
            @endinteract
        </x-table>
    </x-card>

    <livewire:users.update @updated="$refresh"/>
</div>
