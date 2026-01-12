<div>
    <x-button :text="__('Create Category')" wire:click="$toggle('modal')" sm />

    <x-modal :title="__('Create New Market')" wire x-on:open="setTimeout(() => $refs.name.focus(), 250)" size="md" blur="xl">
        <form id="market-create" wire:submit="save" class="space-y-4">

            <div>
                <x-input label="{{ __('Category Name') }} *" wire:model="name" required />
            </div>

        </form>
        <x-slot:footer>
            <x-button type="submit" form="market-create">
                @lang('Save')
            </x-button>
        </x-slot:footer>
    </x-modal>
</div>
