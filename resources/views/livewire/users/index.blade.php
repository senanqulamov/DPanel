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

            @interact('column_created_at', $row)
            {{ $row->created_at->diffForHumans() }}
            @endinteract

            @interact('column_action', $row)
            <div class="flex gap-1">
                <x-button.circle icon="pencil" wire:click="$dispatch('load::user', { 'user' : '{{ $row->id }}'})"/>
                <livewire:users.delete :user="$row" :key="uniqid('', true)" @deleted="$refresh"/>
            </div>
            @endinteract
        </x-table>
    </x-card>

    <livewire:users.update @updated="$refresh"/>
</div>
