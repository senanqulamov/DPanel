<div>
    <x-button :text="__('Create New Market')" wire:click="$toggle('modal')" sm />

    <x-modal :title="__('Create New Market')" wire x-on:open="setTimeout(() => $refs.name.focus(), 250)" size="md" blur="xl">
        <form id="seller-market-create" wire:submit="save" class="space-y-4">
            <div>
                <x-input label="{{ __('Name') }} *" x-ref="name" wire:model="market.name" required />
            </div>

            <div>
                <x-input label="{{ __('Location') }}" wire:model="market.location" />
            </div>

            <div>
                <x-input label="{{ __('Image Path') }}" wire:model="market.image_path" hint="{{ __('URL or path to market image') }}" />
            </div>
        </form>
        <x-slot:footer>
            <x-button type="submit" form="seller-market-create">
                @lang('Save')
            </x-button>
        </x-slot:footer>
    </x-modal>
</div>
